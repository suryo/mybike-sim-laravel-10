# Drivetrain Simulation Physics Reference

This document centralizes the variables and formulas used in the MyBike drivetrain simulation. It serves as a technical reference for understanding the physics engine and biological modeling.

## 1. Global Physical Constants

| Variable                | Symbol     | Value   | Unit     | Description                                             |
| :---------------------- | :--------- | :------ | :------- | :------------------------------------------------------ |
| **Gravity**             | $G$        | `9.81`  | $m/s^2$  | Earth's gravitational acceleration.                     |
| **Air Density**         | $\rho$     | `1.2`   | $kg/m^3$ | Standard air density used for drag calculation.         |
| **Rolling Coefficient** | $C_{rr}$   | `0.005` | —        | Friction coefficient for tires on road.                 |
| **Drag Area**           | $C_dA$     | `0.24`  | $m^2$    | Baseline drag coefficient multiplied by frontal area.   |
| **Wheel Circumference** | $C_{circ}$ | `2.105` | $m$      | Distance traveled per wheel revolution (Standard 700c). |

---

## 2. Drivetrain & Motion

### Gear Ratios

The relationship between front chainring and rear cassette determines the mechanical advantage.
$$Ratio = \frac{\text{Front Teeth}}{\text{Rear Teeth}}$$

### Cadence (RPM)

Calculated based on current speed and gear ratio.
$$Cadence = \frac{v \times 60}{Ratio \times C_{circ}}$$
_Note: $v$ is speed in $m/s$._

### Drive Force

The force applied to the road via the drivetrain depends on whether the rider is actively pedaling.

$$F_{drive} = \begin{cases} \frac{P_{effective}}{v} & \text{if } isPedaling = \text{true} \\ 0 & \text{if } isPedaling = \text{false} \end{cases}$$

- **$isPedaling$**: A boolean state controlled by the user (START/STOP).
- **$P_{effective}$**: The rider's power adjusted for cadence efficiency.
- **Minimum Speed Clamp**: If $v < 0.2$, $v$ is treated as $0.2$ in the calculation to prevent division by zero or infinite force at standstill.

---

## 3. Resistance Forces

The simulation calculates the total resistance force ($F_{res}$) opposing the rider's movement.

### Gravity Force

$$F_{gravity} = \text{mass} \times G \times \sin(\theta)$$
$$\theta = \arctan\left(\frac{Slope\%}{100}\right)$$

### Rolling Resistance

$$F_{roll} = C_{rr} \times \text{mass} \times G \times \cos(\theta)$$

### Aerodynamic Drag

$$F_{drag} = 0.5 \times \rho \times C_dA \times \text{DraftFactor} \times (v - v_{wind})^2 \times \text{sign}(v - v_{wind})$$

- **DraftFactor**: Typically `1.0`, but reduced when drafting another rider.
- **$v_{wind}$**: Headwind is negative, tailwind is positive.

### Total Resistance

$$F_{res} = F_{gravity} + F_{roll} + F_{drag} + F_{brake}$$

- **$F_{brake}$**: Constant force applied when braking (approx. `800 N`).

---

## 4. Bioenergetics & Efficiency

### Cadence Efficiency

Rider efficiency varies with RPM, modeled as a Gaussian bell curve centered at 90 RPM.
$$\eta_{cad} = \max\left(0.15, \exp\left(-\frac{(Cadence - 90)^2}{2 \times 35^2}\right)\right)$$

- **Optimal**: 90 RPM.
- **Drop-off**: Significant below 50 RPM or above 130 RPM.
- **Minimum Floor**: 15% efficiency.

### Heart Rate (BPM)

Models the cardiovascular response to effort and fatigue.
$$HR_{target} = 70 + \left(\frac{P_{rider}}{FTP}\right) \times 110 + (Fatigue \times 0.5)$$
$$HR_{new} = HR_{old} + (HR_{target} - HR_{old}) \times 0.02$$

### Calorie Consumption

$$\text{Joules} = \frac{P_{rider} \times \Delta t}{\eta_{metabolic}}$$
$$\text{kcal} = \frac{\text{Joules}}{4184}$$

- **$\eta_{metabolic}$**: Metabolic efficiency, typically `0.24`.

### Stamina & Fatigue (W' Balance)

Stamina represents the anaerobic tank ($W'$).

- **Depletion**: If $P_{rider} > FTP$:
  $$\Delta W' = \frac{P_{rider} - FTP}{1000} \times \Delta t$$
- **Recovery**: If $P_{rider} \le FTP$:
  $$\Delta W' = \frac{FTP - P_{rider}}{2000} \times \Delta t$$

---

## 5. Numerical Integration

The simulation uses simple Euler integration to update the state every frame.

1.  Calculate **Net Force**: $F_{net} = F_{drive} - F_{res} - F_{brake}$
2.  Calculate **Acceleration**: $a = \frac{F_{net}}{\text{mass}}$
3.  Update **Velocity**: $v_{new} = \max(0, v_{old} + a \times \Delta t)$
4.  Update **Distance**: $D_{new} = D_{old} + v_{new} \times \Delta t$

### States of Motion

The simulation defines three primary states based on effort and speed:
| State | Condition | Physics Behavior |
| :--- | :--- | :--- |
| **PEDALING** | $isPedaling = \text{true}$ | Drive force is applied; bike accelerates towards terminal velocity. |
| **COASTING** | $isPedaling = \text{false}$ and $v > 0.1$ | Drive force is 0; bike decelerates due to resistance forces. |
| **IDLE** | $isPedaling = \text{false}$ and $v \le 0.1$ | Stationary or near-stationary state. |

> [!NOTE]
> The simulation also employs a **Binary Search** in certain modes to find the equilibrium terminal velocity where $F_{drive} = F_{res}$ for predictive UI calculations.

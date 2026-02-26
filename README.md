# MyBike Simulation Project

A web-based bicycle simulation application that calculates real-time rider performance, race progression, and physiological data based on real-world physics. It features a custom route elevation profile, customizable bike models with specific drivetrains, auto/manual shifting logic, and a dynamic race dashboard.

## Physics Engine & Formulas

The core simulation updates the rider's state at 60 FPS ($\Delta t \approx 0.016$s). It employs classical mechanics to determine acceleration, velocity, distance, and energy expenditure. Below are the key formulas used in the simulation loop (`updateSimulation`).

### 1. Driving Force

The driving force is derived from the rider's effective power output ($P_{rider}$ in Watts).
$$F_{drive} = \frac{P_{rider}}{v}$$
_Note: To avoid division by zero when starting from a standstill, the simulation caps the minimum velocity divisor at $0.2 \text{ m/s}$._

$$F_{drive} = \begin{cases} \frac{P_{rider}}{0.2}, & \text{if } v < 0.2 \\ \frac{P_{rider}}{v}, & \text{otherwise} \end{cases}$$

### 2. Resistance Forces

The total resistance ($F_{resistance}$) combating the rider is the sum of three primary forces:

#### a. Gravity Force ($F_{gravity}$)

The force pulling the rider down an incline.
$$F_{gravity} = m \cdot g \cdot \sin(\theta)$$
Where:

- $m$ = Total mass (Rider + Bicycle) in kg
- $g$ = $9.81 \text{ m/s}^2$
- $\theta = \arctan\left(\frac{\text{slope \%}}{100}\right)$

#### b. Aerodynamic Drag ($F_{drag}$)

The air resistance against the rider.
$$F_{drag} = C_{d}A \cdot \text{draft\_factor} \cdot (v - v_{wind})^2 \cdot \text{sign}(v - v_{wind})$$
Where:

- $C_{d}A$ combines the drag coefficient and frontal area (approx $0.4$).
- $\text{draft\_factor}$ = Reduction in drag when closely following another rider.
- $v_{wind}$ = Headwind/Tailwind velocity in m/s.

#### c. Rolling Resistance ($F_{rolling}$)

The friction between the tires and the road surface (applied only when moving).
$$F_{rolling} = C_{rr} \cdot m \cdot g \cdot \cos(\theta)$$
Where $C_{rr}$ is the coefficient of rolling resistance (e.g., $0.005$ for tarmac).

### 3. Net Force and Acceleration

Using Newton's Second Law to find acceleration.
$$F_{net} = F_{drive} - F_{gravity} - F_{drag} - F_{rolling} - F_{brake}$$
$$a = \frac{F_{net}}{m}$$

### 4. Kinematics (Velocity & Distance)

The state updates linearly over the time delta ($\Delta t$).

- **Velocity:** $v_{new} = \max(0, v_{old} + a \cdot \Delta t)$
- **Distance:** $d_{new} = d_{old} + v_{new} \cdot \Delta t$
- **Elevation Gain:** $E_{new} = E_{old} + (v_{new} \cdot \Delta t) \cdot \sin(\theta)$

### 5. Drivetrain & Cadence

Given a selected gear ratio, the simulation computes the required leg cadence.

- **Gear Ratio ($R$):** $R = \frac{\text{Front Chainring Teeth}}{\text{Rear Cassette Cog Teeth}}$
- **Wheel RPM:** $RPM_{wheel} = \frac{v \cdot 60}{C_{wheel}}$ (where $C_{wheel}$ is tire circumference in meters)
- **Cadence (RPM):** $\text{Cadence} = \frac{RPM_{wheel}}{R}$

**Cadence Efficiency:**
A Gaussian curve is used to simulate human biomechanical efficiency, peaking optimally at ~90 RPM.
$$\text{Efficiency} = \max\left(0.15, e^{-\frac{(\text{cadence} - 90)^2}{2 \cdot 35^2}}\right)$$

### 6. Walk/Push (Tuntun) Logic

If the terrain is too steep and the rider's momentum stalls, the simulation forces a dismount:

- **Trigger:** If $v < 0.5 \text{ m/s}$ and $\text{slope} > 2\%$, the rider state switches to `WALKING`.
- **Walking State:** $v$ is fixed at $1.11 \text{ m/s}$ ($\approx 4 \text{ km/h}$).
- **Remount Check:** The system uses a binary search to constantly recalculate the pure physics terminal velocity $V_{pure}$ (where $P_{req} = P_{avail}$) given the terrain. If the slope eases and $V_{pure} > 1.39 \text{ m/s}$ ($\approx 5 \text{ km/h}$), the rider remounts and normal physics resume.

### 7. Physiology (Energy & Heart Rate)

- **Energy Expenditure:** The mechanical work performed is scaled by human metabolic efficiency ($\approx 24\%$).
  $$\Delta \text{Joules} = \frac{P_{rider} \cdot \Delta t}{0.24}$$
- **Calories:** $kcal = \frac{\Delta \text{Joules}}{4184}$
- **Heart Rate:** Dynamically adjusts toward a target HR based on effort relative to Functional Threshold Power (FTP) and accumulated fatigue.
  $$HR_{target} = 70 + \left(\frac{P_{rider}}{\text{FTP}} \cdot 110\right) + (Fatigue \cdot 0.5)$$

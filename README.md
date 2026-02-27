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
### 8. Variabel Ekstra & Pengaruhnya pada Fisika Sepeda

Simulasi ini dirancang secara dinamis sehingga setiap komponen fisik yang menempel pada pengendara maupun sepeda akan memengaruhi kalkulasi:

#### a. Ukuran Ban dan *Rolling Resistance* ($C_{rr}$)
Luas tapak ban dan tekanan udara secara langsung mereduksi atau menambah hambatan gulir ($F_{rolling}$).
- **Ban Lebar (Gravel/MTB)**: Volume tinggi dan tekanan rendah membuat ban mampu meredam getaran jalan rusak, namun area kontak yang luas ke aspal akan meningkatkan nilai $C_{rr}$.
- **Ban Tipis (Road Racing)**: Tekanan tinggi membuat ban sangar keras. Di jalan mulus (aspal), profil area kontak terminimalisir secara drastis, menurunkan nilai $C_{rr}$ (misal $0.003$ vs $0.008$ gravel) membuat kecepatan di rute *flat* (datar) jauh lebih tinggi.
Rumus dasar mengikuti profil gesekan:
$$F_{rolling} = C_{rr}(\text{Lebar Ban}, \text{Tekanan}) \cdot m \cdot g \cdot \cos(\theta)$$

#### b. Pengaruh Total Massa (Berat Sepeda)
Total massa dihitung dari **$m = m_{Rider} + m_{Bike}$**. Dampak massa $m$ tidak merata di semua lanskap:
- **Tanjakan / Incline ($\theta > 0$)**: Massa adalah musuh utama. Semakin berat sepeda, semakin besar nilai $F_{gravity}$ yang menarik ke belakang (karena $\sin(\theta)$ bertambah). Untuk kecepatan menanjak yang sama, sepeda yang berat secara linear memaksa rider mengeluarkan Watt (Power) lebih besar.
- **Turunan / Decline ($\theta < 0$)**: Sepeda yang berat menjadi keuntungan. Massa beralih fungsi menjadi gaya pendorong maju (karena $\sin(-\theta)$ bernilai negatif), menyebabkan momentum inersia menembus *Air Drag* ($F_{drag}$) lebih cepat.
- **Jalan Datar / Flat ($\theta = 0$)**: Pengaruh beban sangat kecil terhadap *Top Speed*, karena $F_{gravity} \approx 0$. Sepeda berat hanya menghambat **Akselerasi** dasar saat percepatan dari 0 ke titik konstan.

#### c. Beban Ekstensi (Tas, Carrier, Pannier)
Sama halnya dengan massa sepeda dasar, massa bagasi langsung diakumulasikan ke total massa ($m = m_{Rider} + m_{Bike} + m_{Baggage}$).
**Dampak Tambahan**: Selain beban gravitasi, pemasangan pannier dan carrier memperluas profil *Frontal Area* ($C_{d}A$).
- **Flat & Turunan**: Pannier akan sangat menahan kecepatan ekstrim karena $F_{drag}$ tumbuh secara kuadratik terhadap profil penampang angin depan sepeda.
- **Tanjakan**: Ekstensi tas akan memicu penalti berganda (berat ekstra + profil aerodinamis yang kendur jika menanjak berangin).

#### d. Material Sepeda (Carbon, Alloy, Steel)
Material bingkai (frame) sepeda menyumbang pada bobot dasar ($m_{Bike}$), namun pada dinamika (*feel* simulasi biomekanika), juga memengaruhi faktor-faktor tersembunyi:
1. **Rasio Kekakuan (Stiffness-to-Weight)**: Frame serat karbon menyalurkan gaya kayuhan kaki ($P_{rider}$) secara nyaris sempurna ke pelek belakang tanpa distorsi melentur (*Flex*). Pada material steel/alloy kelas bawah, sebagian kecil Watt terbuang sebagai panas deformasi elastis, mengurangi efisiensi transmisi $F_{drive}$.
2. **Peredaman Getaran (Damping)**: Material Steel dan Carbon mampu menyerap frekuensi getaran jalan secara parsial. Material Alloy super-kaku justru mementahkan getaran (road vibrations) langsung ke badan pengendara, mempercepat akumulasi **Fatigue** (Kelelahan), yang pada akhirnya akan menekan efisiensi energi kalori rider seiring berjalannya simulasi secara drastis.

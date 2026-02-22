@extends('layouts.admin')

@section('title', 'Bike Fitting Wizard')
@section('subtitle', 'Get professional advice on your bike setup based on your body measurements.')

@section('content')
<div class="fitting-wizard" id="fittingWizard">
    <!-- Progress Bar -->
    <div class="wizard-progress">
        <div class="progress-step active" data-step="1"><span>1</span><label>Type</label></div>
        <div class="progress-step" data-step="2"><span>2</span><label>Usage</label></div>
        <div class="progress-step" data-step="3"><span>3</span><label>Flexibility</label></div>
        <div class="progress-step" data-step="4"><span>4</span><label>Priorities</label></div>
        <div class="progress-step" data-step="5"><span>5</span><label>Angle</label></div>
        <div class="progress-step" data-step="6"><span>6</span><label>Measurements</label></div>
        <div class="progress-step" data-step="7"><span>7</span><label>Result</label></div>
        <div class="progress-line"></div>
    </div>

    <!-- Step 1: Bike Type -->
    <div class="wizard-step active" id="step-1">
        <h2 class="step-title">For which type of bike do you want a bike fit?</h2>
        <div class="selection-grid">
            <div class="select-card" onclick="nextStep(1, {bike_type: 'road'})">
                <div class="card-img" style="background-image: url('https://images.unsplash.com/photo-1485965120184-e220f721d03e?q=80&w=800&auto=format&fit=crop');"></div>
                <div class="card-content">
                    <h3>Road bike</h3>
                    <p>For road bike, gravel bike and cyclocross. Not for time trial bike.</p>
                </div>
            </div>
            <div class="select-card" onclick="nextStep(1, {bike_type: 'mtb'})">
                <div class="card-img" style="background-image: url('https://images.unsplash.com/photo-1544191696-102dbdaeeaa0?q=80&w=800&auto=format&fit=crop');"></div>
                <div class="card-content">
                    <h3>Mountain bike</h3>
                    <p>For cross country, trail bike and fat bike. Not for enduro or downhill.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 2: Usage -->
    <div class="wizard-step" id="step-2">
        <h2 class="step-title">For what usage do you want to optimize your bike fit?</h2>
        <div class="selection-list">
            <div class="list-item" onclick="nextStep(2, {usage: 'flat'})">
                <div class="item-img" style="background-image: url('https://images.unsplash.com/photo-1471506480208-8ebb7e5ed7a2?q=80&w=800&auto=format&fit=crop');"></div>
                <span>Good flat roads</span>
            </div>
            <div class="list-item" onclick="nextStep(2, {usage: 'mixed'})">
                <div class="item-img" style="background-image: url('https://images.unsplash.com/photo-1541625602330-2277a4c4bb99?q=80&w=800&auto=format&fit=crop');"></div>
                <span>Good and bad roads</span>
            </div>
            <div class="list-item" onclick="nextStep(2, {usage: 'mountains'})">
                <div class="item-img" style="background-image: url('https://images.unsplash.com/photo-1517559132351-78bfc7ae111e?q=80&w=800&auto=format&fit=crop');"></div>
                <span>Hills and mountains</span>
            </div>
        </div>
        <button class="btn btn-ghost mt-4" onclick="prevStep(2)">Back</button>
    </div>

    <!-- Step 3: Flexibility -->
    <div class="wizard-step" id="step-3">
        <h2 class="step-title">How flexible are you?</h2>
        <p class="step-desc">Stand up straight, keep your legs straight and bend over.</p>
        <div class="selection-grid-3">
            <div class="select-card-v" onclick="nextStep(3, {flexibility: 'very'})">
                <div class="icon-box">🧘</div>
                <h3>Very flexible</h3>
                <p>My fingertips/hands touch the ground</p>
            </div>
            <div class="select-card-v" onclick="nextStep(3, {flexibility: 'quite'})">
                <div class="icon-box">🧍</div>
                <h3>Quite flexible</h3>
                <p>My fingertips are less than 10 cm from the ground</p>
            </div>
            <div class="select-card-v" onclick="nextStep(3, {flexibility: 'less'})">
                <div class="icon-box">🪵</div>
                <h3>Less flexible</h3>
                <p>My fingertips remain more than 10 cm from the ground</p>
            </div>
        </div>
        <button class="btn btn-ghost mt-4" onclick="prevStep(3)">Back</button>
    </div>

    <!-- Step 4: Priorities -->
    <div class="wizard-step" id="step-4">
        <h2 class="step-title">What are your cycling priorities?</h2>
        <div class="priority-sliders">
            <div class="slider-group">
                <label>Comfort</label>
                <input type="range" min="0" max="100" value="50" class="fitting-slider" id="priorityComfort">
                <label>Speed</label>
            </div>
            <div class="slider-group">
                <label>Long rides</label>
                <input type="range" min="0" max="100" value="50" class="fitting-slider" id="priorityDistance">
                <label>High intensity</label>
            </div>
            <div class="slider-group">
                <label>Cornering</label>
                <input type="range" min="0" max="100" value="50" class="fitting-slider" id="priorityHandling">
                <label>Stability</label>
            </div>
        </div>
        <div class="actions">
            <button class="btn btn-ghost" onclick="prevStep(4)">Back</button>
            <button class="btn btn-primary" onclick="nextStep(4)">Next Step</button>
        </div>
    </div>

    <!-- Step 5: Back Angle -->
    <div class="wizard-step" id="step-5">
        <h2 class="step-title">What back angle do you want on your bike?</h2>
        <div class="angle-visualizer">
            <div class="slider-container">
                <span class="lbl-min">Most aerodynamic (30°)</span>
                <input type="range" min="30" max="60" value="45" class="fitting-slider" id="backAngle" oninput="updateRiderPreview()">
                <span class="lbl-max">Most upright (60°)</span>
            </div>
            <div class="angle-preview-container">
                <svg viewBox="0 0 200 160" class="rider-svg" id="riderPositionSvg">
                    <!-- Ground -->
                    <line x1="10" y1="150" x2="190" y2="150" stroke="#334155" stroke-width="2" />
                    <!-- Wheels -->
                    <circle cx="50" cy="120" r="30" fill="none" stroke="#334155" stroke-width="2" />
                    <circle cx="150" cy="120" r="30" fill="none" stroke="#334155" stroke-width="2" />
                    <!-- Frame Simplified -->
                    <path d="M50 120 L80 120 L100 70 L140 70 L150 120" fill="none" stroke="#475569" stroke-width="3" />
                    
                    <!-- Rider -->
                    <g id="riderGroup">
                        <!-- Leg -->
                        <line x1="90" y1="90" x2="100" y2="110" stroke="#f8fafc" stroke-width="4" stroke-linecap="round" />
                        <!-- Hip -->
                        <circle cx="90" cy="90" r="4" fill="#38bdf8" />
                        <!-- Torso -->
                        <line id="torsoLine" x1="90" y1="90" x2="140" y2="60" stroke="#38bdf8" stroke-width="6" stroke-linecap="round" />
                        <!-- Head -->
                        <circle id="headCircle" cx="145" cy="55" r="8" fill="#38bdf8" />
                        <!-- Arm -->
                        <line id="armLine" x1="140" y1="65" x2="140" y2="90" stroke="#38bdf8" stroke-width="4" stroke-linecap="round" />
                    </g>
                    <!-- Handlebars -->
                    <path d="M135 70 L145 70 L145 80" fill="none" stroke="#475569" stroke-width="3" />
                </svg>
            </div>
        </div>
        <div class="actions">
            <button class="btn btn-ghost" onclick="prevStep(5)">Back</button>
            <button class="btn btn-primary" onclick="nextStep(5)">Next Step</button>
        </div>
    </div>

    <!-- Step 6: Measurements -->
    <div class="wizard-step" id="step-6">
        <h2 class="step-title">Body Measurements (CM)</h2>
        <div class="measurements-layout">
            <div class="guide-panel">
                <div id="measurementGuideVisual" class="guide-visual">
                    <svg viewBox="0 0 200 350" class="human-svg">
                        <!-- Human Silhouette (Simplified) -->
                        <path d="M100 20 Q110 20 115 35 Q120 50 115 65 Q110 80 100 80 Q90 80 85 65 Q80 50 85 35 Q90 20 100 20 Z" fill="#1e293b" stroke="#475569" /> <!-- Head -->
                        <path d="M80 80 L120 80 L135 120 L135 180 L115 220 L120 280 L125 330 L105 330 L100 240 L95 330 L75 330 L80 280 L85 220 L65 180 L65 120 L80 80 Z" fill="#1e293b" stroke="#475569" /> <!-- Body & Legs -->
                        
                        <!-- Measurement Highlights -->
                        <g id="highlight-height" class="m-highlight" style="display:none">
                            <line x1="150" y1="20" x2="150" y2="330" stroke="#38bdf8" stroke-width="2" stroke-dasharray="4,4" />
                            <circle cx="150" cy="20" r="3" fill="#38bdf8" />
                            <circle cx="150" cy="330" r="3" fill="#38bdf8" />
                        </g>
                        <g id="highlight-sternal" class="m-highlight" style="display:none">
                            <line x1="40" y1="80" x2="160" y2="80" stroke="#38bdf8" stroke-width="2" />
                            <circle cx="100" cy="80" r="4" fill="#38bdf8" />
                        </g>
                        <g id="highlight-arm" class="m-highlight" style="display:none">
                            <line x1="120" y1="90" x2="140" y2="160" stroke="#38bdf8" stroke-width="4" stroke-linecap="round" />
                        </g>
                        <g id="highlight-leg" class="m-highlight" style="display:none">
                            <line x1="100" y1="200" x2="100" y2="330" stroke="#38bdf8" stroke-width="3" />
                            <rect x="85" y="200" width="30" height="5" fill="#38bdf8" />
                        </g>
                        <g id="highlight-torso" class="m-highlight" style="display:none">
                            <rect x="85" y="85" width="30" height="95" fill="rgba(56, 189, 248, 0.2)" stroke="#38bdf8" />
                        </g>
                    </svg>
                </div>
                <div id="measurementGuideText" class="guide-text">Select a field to see how to measure.</div>
            </div>
            <div class="inputs-panel">
                <div class="input-row">
                    <label>Height (Head to floor)</label>
                    <input type="number" step="0.1" class="field" id="mHeight" placeholder="e.g. 175" onfocus="showGuide('height')">
                </div>
                <div class="input-row">
                    <label>Sternal Notch (V-bone to floor)</label>
                    <input type="number" step="0.1" class="field" id="mSternal" placeholder="e.g. 145" onfocus="showGuide('sternal')">
                </div>
                <div class="input-row">
                    <label>Arm Length (Shoulder to grip)</label>
                    <input type="number" step="0.1" class="field" id="mArm" placeholder="e.g. 60" onfocus="showGuide('arm')">
                </div>
                <div class="input-row">
                    <label>Inseam / Leg Length (Crotch to floor)</label>
                    <input type="number" step="0.1" class="field" id="mLeg" placeholder="e.g. 80" onfocus="showGuide('leg')">
                </div>
                <div class="input-row">
                    <label>Torso Length (V-bone to seat)</label>
                    <input type="number" step="0.1" class="field" id="mTorso" placeholder="e.g. 65" onfocus="showGuide('torso')">
                </div>
            </div>
        </div>
        <div class="actions">
            <button class="btn btn-ghost" onclick="prevStep(6)">Back</button>
            <button class="btn btn-primary" onclick="submitFitting()">Get Fitting Advice</button>
        </div>
    </div>

    <!-- Step 7: Results -->
    <div class="wizard-step" id="step-7">
        <h2 class="step-title">Your Personalized Fitting Advice</h2>
        <div class="results-grid">
            <div class="result-card main-advice">
                <h3>Recommended Frame Specs</h3>
                <div class="spec-row">
                    <label>Ideal Stack:</label>
                    <span id="adviceStack">-- mm</span>
                </div>
                <div class="spec-row">
                    <label>Ideal Reach:</label>
                    <span id="adviceReach">-- mm</span>
                </div>
                <div class="spec-row">
                    <label>Stack/Reach Ratio:</label>
                    <span id="adviceRatio">--</span>
                </div>
            </div>
            <div class="result-card component-advice">
                <h3>Setup Recommendations</h3>
                <ul id="adviceSetupList">
                    <li>Select a more comfortable saddle height of ~70cm</li>
                    <li>Crank length of 170mm is ideal for your leg length</li>
                </ul>
            </div>
            <div class="result-card geometry-visual">
                <h3>Frame Blueprint</h3>
                <div class="visual-container">
                    <svg viewBox="0 0 800 500" class="geometry-svg" id="geometrySvg">
                        <!-- Frame Diagram -->
                        <!-- Rear Wheel Center (150, 400) -->
                        <circle cx="150" cy="400" r="100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="2" stroke-dasharray="5,5" />
                        <!-- Front Wheel Center (650, 400) -->
                        <circle cx="650" cy="400" r="100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="2" stroke-dasharray="5,5" />
                        
                        <!-- Frame Lines -->
                        <g stroke="#38bdf8" stroke-width="3" fill="none" id="frameLines">
                            <!-- Bottom Bracket (350, 400) -->
                            <!-- Stack/Reach Point (600, 150) - Dynamic -->
                            <path d="M350 400 L250 200 L550 200 L350 400 M250 200 L150 400 M550 200 L650 400" opacity="0.4" />
                        </g>

                        <!-- Dimension Lines (Reach) -->
                        <line x1="350" y1="400" x2="350" y2="100" stroke="#818cf8" stroke-width="1" stroke-dasharray="4,4" />
                        <line x1="350" y1="120" x2="600" y2="120" stroke="#818cf8" stroke-width="2" id="reachLine" />
                        <text x="475" y="110" fill="#818cf8" font-size="20" font-weight="800" text-anchor="middle" id="svgReachVal">REACH</text>

                        <!-- Dimension Lines (Stack) -->
                        <line x1="300" y1="400" x2="650" y2="400" stroke="#818cf8" stroke-width="1" stroke-dasharray="4,4" />
                        <line x1="620" y1="400" x2="620" y2="150" stroke="#818cf8" stroke-width="2" id="stackLine" />
                        <text x="640" y="275" fill="#818cf8" font-size="20" font-weight="800" transform="rotate(-90, 640, 275)" id="svgStackVal">STACK</text>

                        <!-- Dynamic Points -->
                        <circle id="ptStackReach" cx="600" cy="150" r="6" fill="#f8fafc" />
                        <circle cx="350" cy="400" r="6" fill="#f8fafc" />
                        <text x="350" y="430" fill="#f8fafc" font-size="14" font-weight="600" text-anchor="middle">Bottom Bracket</text>
                    </svg>
                </div>
            </div>
        </div>
        <div class="actions">
            <button class="btn btn-ghost" onclick="prevStep(7)">Modify Settings</button>
            <a href="{{ route('admin.riders.index') }}" class="btn btn-primary">Save to Profile</a>
        </div>
    </div>
</div>

<style>
.fitting-wizard { max-width: 900px; margin: 0 auto; padding: 2rem; position: relative; }
.wizard-progress { display: flex; justify-content: space-between; position: relative; margin-bottom: 3rem; }
.progress-line { position: absolute; top: 15px; left: 0; width: 100%; height: 2px; background: rgba(255,255,255,0.1); z-index: 1; }
.progress-step { position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem; width: 60px; }
.progress-step span { width: 32px; height: 32px; border-radius: 50%; background: #1a2332; border: 2px solid rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-weight: 700; transition: all 0.3s; }
.progress-step.active span { background: var(--accent); color: #000; border-color: var(--accent); }
.progress-step label { font-size: 0.65rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); }
.progress-step.active label { color: var(--text-primary); }

.wizard-step { display: none; }
.wizard-step.active { display: block; animation: fadeIn 0.4s ease-out; }

@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.step-title { font-size: 1.75rem; font-weight: 800; margin-bottom: 1.5rem; text-align: center; }
.step-desc { text-align: center; color: var(--text-secondary); margin-bottom: 2rem; }

/* Cards & Grid */
.selection-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem; }
.select-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; cursor: pointer; transition: all 0.3s; }
.select-card:hover { transform: translateY(-5px); border-color: var(--accent); box-shadow: 0 10px 30px rgba(56,189,248,0.15); }
.card-img { height: 200px; background-size: cover; background-position: center; }
.card-content { padding: 1.5rem; }
.card-content h3 { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; }
.card-content p { font-size: 0.9rem; color: var(--text-secondary); }

.selection-list { display: flex; flex-direction: column; gap: 1rem; }
.list-item { display: flex; align-items: center; gap: 1.5rem; background: var(--glass); padding: 1rem 1.5rem; border-radius: 16px; border: 1px solid var(--border); cursor: pointer; }
.list-item:hover { border-color: var(--accent); background: rgba(56,189,248,0.05); }
.item-img { width: 80px; height: 60px; border-radius: 10px; background-size: cover; }

.selection-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; }
.select-card-v { padding: 2rem; text-align: center; background: var(--card-bg); border: 1px solid var(--border); border-radius: 20px; cursor: pointer; }
.select-card-v:hover { border-color: var(--accent); }
.icon-box { font-size: 3rem; margin-bottom: 1.5rem; }

/* Sliders */
.priority-sliders { display: flex; flex-direction: column; gap: 2rem; padding: 2rem; background: var(--glass); border-radius: 24px; }
.slider-group { display: flex; align-items: center; gap: 1.5rem; }
.slider-group label { width: 120px; font-weight: 700; font-size: 0.9rem; }
.fitting-slider { flex: 1; -webkit-appearance: none; height: 6px; border-radius: 3px; background: rgba(255,255,255,0.1); outline: none; }
.fitting-slider::-webkit-slider-thumb { -webkit-appearance: none; width: 24px; height: 24px; border-radius: 50%; background: var(--accent); cursor: pointer; border: 4px solid #0f172a; }

/* Measurements */
.measurements-layout { display: grid; grid-template-columns: 1.2fr 1fr; gap: 3rem; align-items: start; }
.guide-panel { background: #000; border-radius: 20px; padding: 1.5rem; min-height: 400px; display: flex; flex-direction: column; gap: 1.5rem; border: 1px solid var(--border); }
.guide-visual { flex: 1; display: flex; align-items: center; justify-content: center; }
.human-svg { width: 100%; height: 280px; }
.guide-text { color: var(--text-secondary); font-size: 0.9rem; line-height: 1.6; text-align: center; background: rgba(255,255,255,0.02); padding: 1rem; border-radius: 12px; }

.inputs-panel { display: flex; flex-direction: column; gap: 1rem; }
.input-row { display: flex; flex-direction: column; gap: 0.5rem; }

/* Results */
/* Results Visuals */
.geometry-visual { grid-column: span 2; background: #000; padding: 2.5rem; border-radius: 32px; min-height: 450px; position: relative; overflow: hidden; }
.geometry-visual::before { content: ""; position: absolute; inset: 0; background-image: 
    linear-gradient(rgba(56, 189, 248, 0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(56, 189, 248, 0.05) 1px, transparent 1px);
    background-size: 20px 20px; }

.visual-container { position: relative; width: 100%; height: 100%; z-index: 2; display: flex; align-items: center; justify-content: center; }
.geometry-svg { width: 100%; max-width: 600px; height: auto; filter: drop-shadow(0 0 10px rgba(56, 189, 248, 0.2)); }

.angle-preview-container { background: #000; border-radius: 24px; padding: 2rem; margin-top: 1.5rem; display: flex; justify-content: center; border: 1px solid var(--border); }
.rider-svg { width: 300px; height: 250px; }

.actions { display: flex; justify-content: space-between; margin-top: 3rem; }
</style>

<script>
let fittingData = {};
let currentStep = 1;

function nextStep(step, data = {}) {
    Object.assign(fittingData, data);
    
    // Hide current
    document.getElementById(`step-${step}`).classList.remove('active');
    document.querySelector(`.progress-step[data-step="${step}"]`).classList.add('done');
    
    // Show next
    const next = step + 1;
    document.getElementById(`step-${next}`).classList.add('active');
    document.querySelector(`.progress-step[data-step="${next}"]`).classList.add('active');
    
    currentStep = next;
}

function prevStep(step) {
    document.getElementById(`step-${step}`).classList.remove('active');
    document.querySelector(`.progress-step[data-step="${step}"]`).classList.remove('active');
    
    const prev = step - 1;
    document.getElementById(`step-${prev}`).classList.add('active');
    currentStep = prev;
}

function showGuide(field) {
    const guideText = {
        height: "Stand straight against a wall without shoes. Measure your total height from floor to top of head.",
        sternal: "Measure from the ground to the V-shaped dent (Sternal Notch) below your neck.",
        arm: "Hold a pen in your hand. Measure from the transition between arm and shoulder bone down to the center of the pen.",
        leg: "Push a book high between your legs. Measure from ground to top of book (Inseam).",
        torso: "Sit on a stool with your back tight against the wall. Measure from the seat up to your sternal notch."
    };
    document.getElementById('measurementGuideText').innerHTML = `<strong>${field.toUpperCase()}:</strong><br>${guideText[field]}`;
    
    // Toggle highlights
    document.querySelectorAll('.m-highlight').forEach(el => el.style.display = 'none');
    const highlight = document.getElementById(`highlight-${field}`);
    if (highlight) highlight.style.display = 'block';
}

function submitFitting() {
    // Collect measurements
    fittingData.height_cm = document.getElementById('mHeight').value;
    fittingData.sternal_notch_cm = document.getElementById('mSternal').value;
    fittingData.arm_length_cm = document.getElementById('mArm').value;
    fittingData.leg_length_cm = document.getElementById('mLeg').value;
    fittingData.torso_length_cm = document.getElementById('mTorso').value;
    fittingData.back_angle_preference = document.getElementById('backAngle').value;

    calculateAdvice();
    nextStep(6);
}

function calculateAdvice() {
    const inseam = parseFloat(fittingData.leg_length_cm);
    const torso = parseFloat(fittingData.torso_length_cm);
    const arm = parseFloat(fittingData.arm_length_cm);
    const h = parseFloat(fittingData.height_cm);
    const angle = parseInt(fittingData.back_angle_preference);

    if (!inseam || !torso) return;

    // Classic Lemond/Guimard adjusted for modern Stack/Reach
    // Stack is primarily driven by inseam and preferred back angle
    // Higher angle (upright) -> More stack
    let baseStack = inseam * 0.69; 
    let stackMod = (angle - 45) * 1.5; // Upright (+), Aero (-)
    let finalStack = baseStack + stackMod;

    // Reach is driven by torso + arm length, and back angle
    let baseReach = (torso + arm) * 0.45;
    let reachMod = (45 - angle) * 0.8; // Upright (-), Aero (+)
    let finalReach = baseReach + reachMod;

    document.getElementById('adviceStack').innerText = Math.round(finalStack) + ' mm';
    document.getElementById('adviceReach').innerText = Math.round(finalReach) + ' mm';
    document.getElementById('adviceRatio').innerText = (finalStack / finalReach).toFixed(2);

    // Dynamic advice list
    const list = document.getElementById('adviceSetupList');
    list.innerHTML = '';
    
    const saddleHeight = inseam * 0.883;
    list.innerHTML += `<li>Saddle Height (C-T): <strong>${Math.round(saddleHeight * 10) / 10} cm</strong></li>`;
    
    const crank = inseam < 78 ? 165 : (inseam > 88 ? 175 : 170);
    list.innerHTML += `<li>Recommended Crank Length: <strong>${crank} mm</strong></li>`;
    
    if (finalStack / finalReach > 1.55) {
        list.innerHTML += `<li>You prefer an <strong>Endurance</strong> fit for maximum comfort.</li>`;
    } else {
        list.innerHTML += `<li>You prefer a <strong>Performance/Aero</strong> fit for maximum speed.</li>`;
    }

    updateGeometrySvg(finalStack, finalReach);
}

function updateRiderPreview() {
    const angle = parseInt(document.getElementById('backAngle').value);
    const torsoLine = document.getElementById('torsoLine');
    const headCircle = document.getElementById('headCircle');
    const armLine = document.getElementById('armLine');

    // Simple trigonometry to rotate torso
    // 90, 90 is hip
    const rad = (angle) * (Math.PI / 180);
    const torsoLen = 70;
    const x2 = 90 + Math.cos(rad) * torsoLen;
    const y2 = 90 - Math.sin(rad) * torsoLen;

    torsoLine.setAttribute('x2', x2);
    torsoLine.setAttribute('y2', y2);
    
    headCircle.setAttribute('cx', x2 + Math.cos(rad) * 10);
    headCircle.setAttribute('cy', y2 - Math.sin(rad) * 10);

    // Arm to bars (static bars at 145, 75)
    armLine.setAttribute('x1', x2);
    armLine.setAttribute('y1', y2);
    armLine.setAttribute('x2', 142);
    armLine.setAttribute('y2', 72);
}

function updateGeometrySvg(stack, reach) {
    const bbX = 350;
    const bbY = 400;
    
    // Scale for SVG (assume 1mm = 0.5px or similar)
    // Range is approx 400mm - 700mm
    const scale = 0.4;
    const drawStack = stack * scale;
    const drawReach = reach * scale;

    const ptX = bbX + drawReach;
    const ptY = bbY - drawStack;

    // Update lines
    document.getElementById('reachLine').setAttribute('x2', ptX);
    document.getElementById('reachLine').setAttribute('y1', ptY - 30);
    document.getElementById('reachLine').setAttribute('y2', ptY - 30);
    document.getElementById('reachLine').setAttribute('x1', bbX);

    document.getElementById('stackLine').setAttribute('y2', ptY);
    document.getElementById('stackLine').setAttribute('x1', ptX + 30);
    document.getElementById('stackLine').setAttribute('x2', ptX + 30);
    document.getElementById('stackLine').setAttribute('y1', bbY);

    // Update labels position
    document.getElementById('svgReachVal').setAttribute('x', bbX + drawReach/2);
    document.getElementById('svgReachVal').setAttribute('y', ptY - 45);
    document.getElementById('svgReachVal').textContent = Math.round(reach) + ' mm';

    document.getElementById('svgStackVal').setAttribute('x', ptX + 55);
    document.getElementById('svgStackVal').setAttribute('y', bbY - drawStack/2);
    document.getElementById('svgStackVal').textContent = Math.round(stack) + ' mm';
    document.getElementById('svgStackVal').setAttribute('transform', `rotate(-90, ${ptX + 55}, ${bbY - drawStack/2})`);

    // Update Point
    document.getElementById('ptStackReach').setAttribute('cx', ptX);
    document.getElementById('ptStackReach').setAttribute('cy', ptY);
}

// Initialize preview
document.addEventListener('DOMContentLoaded', () => {
    updateRiderPreview();
});
</script>
@endsection

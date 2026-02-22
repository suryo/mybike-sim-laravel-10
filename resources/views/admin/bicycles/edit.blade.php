@extends('layouts.admin')

@section('title', 'Edit Bicycle')
@section('subtitle', 'Update all specifications for: ' . $bicycle->name)

@section('actions')
<a href="{{ route('admin.bicycles.index') }}" class="btn btn-ghost">← Back to List</a>
@endsection

@section('content')
<style>
.create-layout { display: grid; grid-template-columns: 1fr 950px; gap: 1.5rem; align-items: start; }
.form-col { min-width: 0; }
.preview-col { position: sticky; top: 1rem; width: 950px; }
.preview-panel { background: linear-gradient(160deg,#1a3a5c,#0d2340); border: 1px solid rgba(56,189,248,.2); border-radius: 16px; overflow: hidden; }
.preview-header { display: flex; align-items: center; justify-content: space-between; padding: .75rem 1rem; border-bottom: 1px solid rgba(56,189,248,.15); }
.preview-header span { font-size: .68rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #93c5fd; }
.preview-header .status { font-size: .65rem; color: #38bdf8; opacity: .7; }
#previewCanvas { display: block; width: 100%; aspect-ratio: 950/650; }
.preview-stats { padding: .75rem 1rem; border-top: 1px solid rgba(56,189,248,.1); display: grid; grid-template-columns: 1fr 1fr 1fr; gap: .5rem; }
.stat-item { text-align: center; }
.stat-val { font-size: .9rem; font-weight: 700; color: #38bdf8; }
.stat-lbl { font-size: .6rem; color: #93c5fd; opacity: .7; text-transform: uppercase; letter-spacing: .08em; }
.section-card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 1.5rem; margin-bottom: 1.25rem; }
.section-title { font-size: .68rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--accent); margin-bottom: 1.25rem; padding-bottom: .65rem; border-bottom: 1px solid var(--border); }
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; }
.form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.1rem; }
.form-grid-4 { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1.1rem; }
.form-group label { display: block; font-size: .75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: .35rem; }
.form-group .hint { font-size: .65rem; color: var(--text-secondary); opacity: .55; margin-top: .25rem; }
.field { width: 100%; padding: .6rem .85rem; background: rgba(0,0,0,.25); border: 1px solid var(--border); border-radius: 8px; color: white; font-size: .83rem; transition: border-color .15s; box-sizing: border-box; }
.field:focus { outline: none; border-color: var(--accent); }
.geo-field:not(:placeholder-shown) { border-color: rgba(56,189,248,.4); }
.error-msg { font-size: .7rem; color: #f87171; margin-top: .25rem; }
.form-footer { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border); }
</style>

@php
    $gFront = old('front_gears_raw', is_array($bicycle->front_gears) ? implode(',', $bicycle->front_gears) : '');
    $gRear  = old('rear_gears_raw',  is_array($bicycle->rear_gears)  ? implode(',', $bicycle->rear_gears)  : '');
@endphp

<form action="{{ route('admin.bicycles.update', $bicycle) }}" method="POST" id="bikeForm">
@csrf
@method('PATCH')

<div class="create-layout">
  <!-- ── Left: form ── -->
  <div class="form-col">

    {{-- 1. General --}}
    <div class="section-card">
        <div class="section-title">① General Information</div>
        <div class="form-grid-2">
            <div class="form-group">
                <label>Bicycle Name *</label>
                <input type="text" name="name" value="{{ old('name',$bicycle->name) }}" class="field" required>
            </div>
            <div class="form-group">
                <label>Category *</label>
                <select name="bicycle_category_id" class="field" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('bicycle_category_id',$bicycle->bicycle_category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Type *</label>
                <input type="text" name="type" value="{{ old('type',$bicycle->type) }}" class="field" required>
            </div>
            <div class="form-group">
                <label>Color / Colorway</label>
                <input type="text" name="color" value="{{ old('color',$bicycle->color) }}" class="field">
            </div>
            <div class="form-group">
                <label>Frame Material</label>
                <select name="frame_material" class="field">
                    @foreach(['Aluminum','Carbon','Steel','Titanium'] as $m)
                        <option {{ old('frame_material',$bicycle->frame_material)==$m?'selected':'' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Fork Material</label>
                <select name="fork_material" class="field">
                    @foreach(['Carbon','Aluminum','Steel'] as $m)
                        <option {{ old('fork_material',$bicycle->fork_material)==$m?'selected':'' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Total Bike Weight (kg) *</label>
                <input type="number" step="0.01" name="weight_kg" value="{{ old('weight_kg',$bicycle->weight_kg) }}" class="field" required>
            </div>
            <div class="form-group">
                <label>Tire Width (mm)</label>
                <input type="number" step="1" name="tire_width" id="f_tire_width" value="{{ old('tire_width',$bicycle->tire_width) }}" class="field geo-field">
            </div>
        </div>
    </div>

    {{-- 2. Geometry --}}
    <div class="section-card">
        <div class="section-title">② Frame Geometry (mm / degrees)</div>
        <div class="form-grid-4">
            <div class="form-group">
                <label>Stack (mm)</label>
                <input type="number" step="0.1" name="stack" id="f_stack" value="{{ old('stack',$bicycle->stack) }}" class="field geo-field">
                <div class="hint">Vertical BB→HT</div>
            </div>
            <div class="form-group">
                <label>Reach (mm)</label>
                <input type="number" step="0.1" name="reach" id="f_reach" value="{{ old('reach',$bicycle->reach) }}" class="field geo-field">
                <div class="hint">Horizontal BB→HT</div>
            </div>
            <div class="form-group">
                <label>HT Angle (°)</label>
                <input type="number" step="0.01" name="head_tube_angle" id="f_hta" value="{{ old('head_tube_angle',$bicycle->head_tube_angle) }}" class="field geo-field">
            </div>
            <div class="form-group">
                <label>ST Angle (°)</label>
                <input type="number" step="0.01" name="seat_tube_angle" id="f_sta" value="{{ old('seat_tube_angle',$bicycle->seat_tube_angle) }}" class="field">
            </div>
            <div class="form-group">
                <label>HT Length (mm)</label>
                <input type="number" step="0.1" name="head_tube_length" id="f_htl" value="{{ old('head_tube_length',$bicycle->head_tube_length) }}" class="field geo-field">
            </div>
            <div class="form-group">
                <label>ST Length (mm)</label>
                <input type="number" step="0.1" name="seat_tube_length" id="f_stl" value="{{ old('seat_tube_length',$bicycle->seat_tube_length) }}" class="field">
                <div class="hint">C-T</div>
            </div>
            <div class="form-group">
                <label>Top Tube (mm)</label>
                <input type="number" step="0.1" name="top_tube_length" value="{{ old('top_tube_length',$bicycle->top_tube_length) }}" class="field">
            </div>
            <div class="form-group">
                <label>Chainstay (mm)</label>
                <input type="number" step="0.1" name="chainstay_length" id="f_cs" value="{{ old('chainstay_length',$bicycle->chainstay_length) }}" class="field geo-field">
            </div>
            <div class="form-group">
                <label>BB Drop (mm)</label>
                <input type="number" step="0.1" name="bb_drop" id="f_bbd" value="{{ old('bb_drop',$bicycle->bb_drop) }}" class="field geo-field">
                <div class="hint">Positive = below axle</div>
            </div>
            <div class="form-group">
                <label>Wheelbase (mm)</label>
                <input type="number" step="0.1" name="wheelbase" id="f_wb" value="{{ old('wheelbase',$bicycle->wheelbase) }}" class="field geo-field">
            </div>
            <div class="form-group">
                <label>Fork Offset (mm)</label>
                <input type="number" step="0.1" name="fork_offset" value="{{ old('fork_offset',$bicycle->fork_offset) }}" class="field">
            </div>
            <div class="form-group">
                <label>Wheel Diam. (mm)</label>
                <input type="number" step="1" name="wheel_diameter" id="f_wd" value="{{ old('wheel_diameter',$bicycle->wheel_diameter??700) }}" class="field geo-field">
                <div class="hint">700c / 650b</div>
            </div>
        </div>
    </div>

    {{-- 3. Drivetrain --}}
    <div class="section-card">
        <div class="section-title">③ Drivetrain & Gearing</div>
        <div class="form-grid-2">
            <div class="form-group">
                <label>Front Chainrings</label>
                <input type="text" name="front_gears_raw" value="{{ $gFront }}" class="field" placeholder="e.g. 40  or  50,34">
            </div>
            <div class="form-group">
                <label>Rear Cassette</label>
                <input type="text" name="rear_gears_raw" value="{{ $gRear }}" class="field">
            </div>
            <div class="form-group">
                <label>Crank Length (mm)</label>
                <input type="number" step="0.5" name="crank_length_mm" value="{{ old('crank_length_mm',$bicycle->crank_length_mm??172.5) }}" class="field">
            </div>
            <div class="form-group">
                <label>Drivetrain Efficiency</label>
                <input type="number" step="0.001" name="efficiency" value="{{ old('efficiency',$bicycle->efficiency??0.98) }}" class="field" min="0.8" max="1.0">
            </div>
        </div>
    </div>

    {{-- 4. Aero --}}
    <div class="section-card">
        <div class="section-title">④ Aerodynamics & Rolling Resistance</div>
        <div class="form-grid-2">
            <div class="form-group">
                <label>Drag Coefficient (CdA)</label>
                <input type="number" step="0.001" name="drag_coefficient" value="{{ old('drag_coefficient',$bicycle->drag_coefficient??0.4) }}" class="field">
                <div class="hint">Aero: ~0.30 · Gravel: ~0.42</div>
            </div>
            <div class="form-group">
                <label>Rolling Resistance (Crr)</label>
                <input type="number" step="0.0001" name="rolling_coefficient" value="{{ old('rolling_coefficient',$bicycle->rolling_coefficient??0.005) }}" class="field">
            </div>
        </div>
    </div>

    {{-- 5. Simulation --}}
    <div class="section-card">
        <div class="section-title">⑤ Default Simulation Parameters</div>
        <div class="form-grid-3">
            <div class="form-group">
                <label>Rider Weight (kg)</label>
                <input type="number" step="0.1" name="rider_weight" value="{{ old('rider_weight',$bicycle->rider_weight??75) }}" class="field">
            </div>
            <div class="form-group">
                <label>Bike Weight – Sim (kg)</label>
                <input type="number" step="0.01" name="bicycle_weight" value="{{ old('bicycle_weight',$bicycle->bicycle_weight??$bicycle->weight_kg) }}" class="field">
            </div>
            <div class="form-group">
                <label>Max Heart Rate (bpm)</label>
                <input type="number" step="1" name="max_hr" value="{{ old('max_hr',$bicycle->max_hr??190) }}" class="field">
            </div>
            <div class="form-group">
                <label>FTP (W)</label>
                <input type="number" step="1" name="ftp" value="{{ old('ftp',$bicycle->ftp??250) }}" class="field">
            </div>
            <div class="form-group">
                <label>Max Power (W)</label>
                <input type="number" step="1" name="max_power_output" value="{{ old('max_power_output',$bicycle->max_power_output??350) }}" class="field">
            </div>
            <div class="form-group">
                <label>Aerobic Threshold (W)</label>
                <input type="number" step="1" name="aerobic_threshold" value="{{ old('aerobic_threshold',$bicycle->aerobic_threshold??180) }}" class="field">
            </div>
            <div class="form-group">
                <label>Anaerobic Threshold (W)</label>
                <input type="number" step="1" name="anaerobic_threshold" value="{{ old('anaerobic_threshold',$bicycle->anaerobic_threshold??280) }}" class="field">
            </div>
            <div class="form-group">
                <label>Initial Distance (km)</label>
                <input type="number" step="0.1" name="initial_distance" value="{{ old('initial_distance',$bicycle->initial_distance??0) }}" class="field">
            </div>
            <div class="form-group">
                <label>Initial Elevation (m)</label>
                <input type="number" step="1" name="initial_elevation" value="{{ old('initial_elevation',$bicycle->initial_elevation??0) }}" class="field">
            </div>
        </div>
    </div>

    <div class="form-footer">
        <a href="{{ route('admin.bicycles.index') }}" class="btn btn-ghost">Cancel</a>
        <button type="submit" class="btn btn-primary" style="padding:.75rem 2.5rem;min-width:180px;">💾 Update Bicycle</button>
    </div>
  </div>{{-- /form-col --}}

  <!-- ── Right: live preview ── -->
  <div class="preview-col">
    <div class="preview-panel">
        <div class="preview-header">
            <span>📐 Live Preview</span>
            <span class="status" id="preview-status">Loading…</span>
        </div>
        <canvas id="previewCanvas" width="1900" height="1300"></canvas>
        <div class="preview-stats">
            <div class="stat-item"><div class="stat-val" id="st-trail">–</div><div class="stat-lbl">Trail (mm)</div></div>
            <div class="stat-item"><div class="stat-val" id="st-ratio">–</div><div class="stat-lbl">Stack/Reach</div></div>
            <div class="stat-item"><div class="stat-val" id="st-wb">–</div><div class="stat-lbl">Wheelbase</div></div>
        </div>
    </div>
  </div>
</div>
</form>

<script>
const fStack = () => parseFloat(document.getElementById('f_stack')?.value)  || 0;
const fReach = () => parseFloat(document.getElementById('f_reach')?.value)  || 0;
const fHTA   = () => parseFloat(document.getElementById('f_hta')?.value)    || 73;
const fHTL   = () => parseFloat(document.getElementById('f_htl')?.value)    || 0;
const fCS    = () => parseFloat(document.getElementById('f_cs')?.value)     || 415;
const fBBD   = () => parseFloat(document.getElementById('f_bbd')?.value)    || 70;
const fWB    = () => parseFloat(document.getElementById('f_wb')?.value)     || 0;
const fWD    = () => parseFloat(document.getElementById('f_wd')?.value)     || 700;

document.querySelectorAll('.geo-field').forEach(el => el.addEventListener('input', drawPreview));
['f_hta','f_stl','f_sta'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', drawPreview);
});

function drawPreview() {
    const canvas = document.getElementById('previewCanvas');
    const ctx = canvas.getContext('2d');
    const W = canvas.width, H = canvas.height;
    ctx.clearRect(0,0,W,H);
    const bg = ctx.createLinearGradient(0,0,0,H);
    bg.addColorStop(0,'#1a3a5c'); bg.addColorStop(1,'#0d2340');
    ctx.fillStyle = bg; ctx.fillRect(0,0,W,H);

    const stack=fStack(), reach=fReach(), hta=fHTA(), htl=fHTL();
    const cs=fCS(), bbd=fBBD(), wb=fWB(), wd=fWD();
    const wheelR_mm = wd/2;
    const hasGeo = stack>100 && reach>50 && (wb>500||cs>200);

    if (!hasGeo) {
        ctx.font='13px Inter'; ctx.fillStyle='rgba(147,197,253,.3)'; ctx.textAlign='center';
        const gr=H*0.27;
        [[W/2-gr*.8,H*.68],[W/2+gr*.8,H*.68]].forEach(([x,y])=>{
            ctx.beginPath(); ctx.arc(x,y,gr,0,Math.PI*2);
            ctx.strokeStyle='rgba(56,189,248,.06)'; ctx.lineWidth=3; ctx.stroke();
        });
        ctx.fillText('Fill in geometry fields to preview',W/2,H/2-10);
        document.getElementById('preview-status').textContent='Awaiting geometry…';
        ['st-trail','st-ratio','st-wb'].forEach(id=>document.getElementById(id).textContent='–');
        return;
    }

    const wheelbase = wb || cs*2.2;
    const MT=30, MB=24, MH=40;
    const totalH_mm = 2*wheelR_mm - Math.min(bbd,80) + stack;
    const totalW_mm = wheelR_mm + wheelbase + wheelR_mm;
    const scale = Math.min((H-MT-MB)/totalH_mm, (W-MH*2)/totalW_mm);

    const groundY = H-MB;
    const axleY   = groundY - wheelR_mm*scale;
    const startX  = (W - totalW_mm*scale)/2 + wheelR_mm*scale;
    const color   = '#38bdf8';
    const line = (x1,y1,x2,y2) => { ctx.beginPath(); ctx.moveTo(x1,y1); ctx.lineTo(x2,y2); ctx.stroke(); };

    ctx.strokeStyle='rgba(56,189,248,.15)'; ctx.lineWidth=1; ctx.setLineDash([6,6]);
    line(20,groundY,W-20,groundY); ctx.setLineDash([]);

    const rearAxle  = {x:startX, y:axleY};
    const frontAxle = {x:startX+wheelbase*scale, y:axleY};
    [rearAxle,frontAxle].forEach(ax=>{
        const r=wheelR_mm*scale;
        ctx.strokeStyle=color+'55'; ctx.lineWidth=2;
        ctx.beginPath(); ctx.arc(ax.x,ax.y,r,0,Math.PI*2); ctx.stroke();
        ctx.strokeStyle=color+'28'; ctx.lineWidth=1;
        ctx.beginPath(); ctx.arc(ax.x,ax.y,r*.90,0,Math.PI*2); ctx.stroke();
        ctx.strokeStyle=color+'60'; ctx.lineWidth=2;
        ctx.beginPath(); ctx.arc(ax.x,ax.y,r*.12,0,Math.PI*2); ctx.stroke();
        ctx.strokeStyle=color+'15'; ctx.lineWidth=1;
        for(let a=0;a<Math.PI*2;a+=Math.PI/6){
            line(ax.x+r*.12*Math.cos(a),ax.y+r*.12*Math.sin(a),ax.x+r*.88*Math.cos(a),ax.y+r*.88*Math.sin(a));
        }
    });

    const bbY = axleY + bbd*scale;
    const csRun = Math.sqrt(Math.max(0,cs*cs-bbd*bbd))*scale;
    const bb = {x:rearAxle.x+csRun, y:bbY};
    const htaRad = hta*Math.PI/180;
    const htTop  = {x:bb.x+reach*scale, y:bb.y-stack*scale};
    const htBottom={x:htTop.x+(htl||140)*Math.cos(htaRad), y:htTop.y+(htl||140)*Math.sin(htaRad)};
    const sta=parseFloat(document.getElementById('f_sta')?.value)||74;
    const stl=parseFloat(document.getElementById('f_stl')?.value)||440;
    const staRad=sta*Math.PI/180;
    const stTopX=bb.x-stl*scale*Math.cos(staRad);
    const stTopY=bb.y-stl*scale*Math.sin(staRad);

    ctx.strokeStyle=color+'55'; ctx.lineWidth=2;
    line(rearAxle.x,rearAxle.y,bb.x,bb.y);
    line(stTopX,stTopY,rearAxle.x,rearAxle.y);

    ctx.strokeStyle=color; ctx.lineWidth=2.5;
    line(bb.x,bb.y,stTopX,stTopY);
    line(stTopX,stTopY,htTop.x,htTop.y);
    line(bb.x,bb.y,htBottom.x,htBottom.y);
    ctx.lineWidth=5; line(htTop.x,htTop.y,htBottom.x,htBottom.y);
    ctx.lineWidth=2.5; ctx.strokeStyle=color+'BB';
    line(htBottom.x,htBottom.y,frontAxle.x,frontAxle.y);

    ctx.fillStyle=color; ctx.beginPath(); ctx.arc(bb.x,bb.y,4,0,Math.PI*2); ctx.fill();

    ctx.strokeStyle='rgba(56,189,248,.2)'; ctx.lineWidth=1; ctx.setLineDash([3,4]);
    line(bb.x,bb.y,bb.x,htTop.y);
    line(bb.x,htTop.y,htTop.x,htTop.y);
    ctx.setLineDash([]);

    ctx.font='600 10px Inter'; ctx.fillStyle='#93c5fd'; ctx.textAlign='left';
    if(stack>0) ctx.fillText(stack,bb.x+3,(bb.y+htTop.y)/2);
    if(reach>0) ctx.fillText(reach,bb.x+reach*scale/2-12,htTop.y-5);

    document.getElementById('preview-status').textContent='Live preview';
    const fo=parseFloat(document.querySelector('[name="fork_offset"]')?.value)||45;
    const trail=((wheelR_mm*Math.cos(htaRad)-fo)/Math.sin(htaRad)).toFixed(1);
    document.getElementById('st-trail').textContent=isNaN(+trail)?'–':trail;
    document.getElementById('st-ratio').textContent=reach>0?(stack/reach).toFixed(3):'–';
    document.getElementById('st-wb').textContent=wb>0?Math.round(wb):'–';
}

drawPreview();
</script>
@endsection

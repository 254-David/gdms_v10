function showPage(page,el){
    document.querySelectorAll('.pg').forEach(s=>s.classList.remove('active'));
    document.querySelectorAll('.nav-i').forEach(n=>n.classList.remove('active'));
    const p=document.getElementById('pg-'+page);
    if(p) p.classList.add('active');
    if(el) el.classList.add('active');
    else{ const btn=document.querySelector(`[onclick*="'${page}'"]`); if(btn)btn.classList.add('active'); }
    const titles={
        dashboard:'Dashboard Overview',deliveries:'Milk Deliveries',
        quality:'Quality Monitor',spoilage:'Spoilage Detection',
        farmers:'Farmer Management',payments:'Payment Processing',
        storage:'Storage Tanks',reports:'Reports & Analytics',
        'ai-advisor':'AI Quality Advisor',announcements:'Announcements',
        'staff-mgmt':'Staff Management',messages:'Contact Messages',
        settings:'System Settings',logs:'Activity Logs'
    };
    document.getElementById('tb-title').textContent=titles[page]||page;
}
function openModal(id){document.getElementById(id).classList.add('show');document.body.style.overflow='hidden';}
function closeModal(id){document.getElementById(id).classList.remove('show');document.body.style.overflow='';}
document.addEventListener('click',function(e){if(e.target.classList.contains('ov'))closeModal(e.target.id);});

function toast(msg,type='s'){
    let t=document.getElementById('toast');
    if(!t){t=document.createElement('div');t.id='toast';t.className='toast';document.body.appendChild(t);}
    const icon=type==='s'?'✅':type==='e'?'❌':'ℹ️';
    t.className=`toast toast-${type==='s'?'s':type==='e'?'e':'i'}`;
    t.innerHTML=`<span style="font-size:16px;">${icon}</span><span>${msg}</span>`;
    t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3500);
}

// Show generated credentials in a prominent modal overlay
function showCredentials(id, password, role){
    let overlay = document.getElementById('credOverlay');
    if(!overlay){
        overlay = document.createElement('div');
        overlay.id = 'credOverlay';
        overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:9999;display:flex;align-items:center;justify-content:center;';
        document.body.appendChild(overlay);
    }
    overlay.innerHTML = `
    <div style="background:#ffffff;border:1px solid rgba(16,217,126,0.35);border-radius:18px;padding:36px 40px;max-width:420px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.6);">
      <div style="width:52px;height:52px;border-radius:14px;background:rgba(5,150,105,0.15);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:26px;">&#x1F510;</div>
      <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:#0f172a;margin-bottom:6px;">${role} Created!</h3>
      <p style="font-size:13px;color:rgba(15,23,42,0.6);margin-bottom:24px;">Save these credentials — the password will not be shown again.</p>
      <div style="background:#f1f5f9;border:1px solid rgba(16,217,126,0.2);border-radius:12px;padding:16px 20px;text-align:left;margin-bottom:20px;">
        <div style="margin-bottom:10px;"><span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;">${role} ID</span><br><span style="font-size:16px;font-weight:700;color:#0f172a;letter-spacing:1px;">${id}</span></div>
        <div><span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;">Temporary Password</span><br>
          <div style="display:flex;align-items:center;gap:10px;margin-top:4px;">
            <span style="font-size:18px;font-weight:700;color:#059669;letter-spacing:2px;font-family:monospace;">${password}</span>
            <button onclick="navigator.clipboard.writeText(this.dataset.pw);this.innerHTML='Copied'" data-pw="${password}" style="background:rgba(5,150,105,0.15);border:1px solid rgba(16,217,126,0.3);border-radius:7px;color:#059669;font-size:11px;font-weight:700;padding:4px 10px;cursor:pointer;">Copy</button>
          </div>
        </div>
      </div>
      <p style="font-size:12px;color:rgba(245,158,11,0.9);margin-bottom:18px;">&#9888; The user must change this password after first login.</p>
      <button onclick="document.getElementById('credOverlay').remove()" style="background:linear-gradient(135deg,#0ab568,#10d97e);color:white;font-size:14px;font-weight:700;padding:11px 32px;border:none;border-radius:10px;cursor:pointer;width:100%;">Got it, close</button>
    </div>`;
    overlay.style.display = 'flex';
}


function filterTbl(input,id){
    const v=input.value.toLowerCase();
    document.querySelectorAll('#'+id+' tbody tr').forEach(r=>{
        r.style.display=r.textContent.toLowerCase().includes(v)?'':'none';
    });
}

async function submitDelivery(){
    const form=document.getElementById('dlvForm');
    const data=new FormData(form);
    const btn=document.getElementById('dlvBtn');
    btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Saving...';
    try{
        const r=await fetch('../php/record_delivery.php',{method:'POST',body:data});
        const d=await r.json();
        if(d.success){
            closeModal('dlvModal');
            toast('Delivery saved! '+(d.grade?'Grade '+d.grade:'Ungraded')+' | Spoilage: '+d.spoilage_risk,'s');
            form.reset();setTimeout(()=>location.reload(),2200);
        }else toast(d.message||'Failed to save delivery','e');
    }catch(e){toast('Network error — check PHP files','e');}
    btn.disabled=false;btn.innerHTML='<i class="fas fa-save"></i> Save Delivery';
}

async function submitFarmer(){
    const form=document.getElementById('farmerForm');
    const data=new FormData(form);
    const btn=document.getElementById('farmerBtn');
    btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Adding...';
    try{
        const r=await fetch('../php/add_farmer.php',{method:'POST',body:data});
        const d=await r.json();
        if(d.success){closeModal('farmerModal');showCredentials(d.farmer_id,d.temp_password,'Farmer');form.reset();setTimeout(()=>location.reload(),3500);}
        else toast(d.message||'Failed','e');
    }catch(e){toast('Network error','e');}
    btn.disabled=false;btn.innerHTML='<i class="fas fa-user-plus"></i> Add Farmer';
}

async function payPreview(farmerId){
    const start=document.getElementById('pay-start').value;
    const end=document.getElementById('pay-end').value;
    if(!farmerId||!start||!end) return;
    const res=await fetch(`../php/get_payment_preview.php?farmer_id=${farmerId}&start=${start}&end=${end}`);
    const d=await res.json();
    const pv=document.getElementById('pay-preview');
    pv.style.display='block';
    if(d.success && d.total_litres>0){
        const rows=d.breakdown.map(b=>`<tr>
            <td style="padding:6px 10px;color:#1e293b;">Grade ${b.quality_grade||'—'}</td>
            <td style="padding:6px 10px;">${parseFloat(b.litres).toFixed(1)}L</td>
            <td style="padding:6px 10px;">KES ${parseFloat(b.amount).toFixed(2)}</td>
        </tr>`).join('');
        const rejWarn=d.rejected_count>0?`<div style="margin-top:10px;padding:10px 12px;background:rgba(239,68,68,0.1);border-radius:9px;border-left:3px solid #ee0b0bff;font-size:12px;color:#eb0101;">
            ⚠️ ${d.rejected_count} rejected delivery(ies) — ${parseFloat(d.rejected_litres).toFixed(1)}L excluded (rejected milk is not payable)</div>`:'';
        pv.innerHTML=`
        <table style="width:100%;font-size:12px;margin-bottom:12px;border-collapse:collapse;background:rgba(0,0,0,0.15);border-radius:8px;overflow:hidden;">
          <tr style="background:rgba(5,150,105,0.1);">
            <th style="padding:8px 10px;text-align:left;font-size:11px;color:#64748b;">Grade</th>
            <th style="padding:8px 10px;text-align:left;font-size:11px;color:#64748b;">Litres</th>
            <th style="padding:8px 10px;text-align:left;font-size:11px;color:#64748b;">Base Amount</th>
          </tr>${rows}
        </table>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
          <div style="text-align:center;background:#d1fae5;border-radius:12px;border:1.5px solid #6ee7b7;padding:10px;">
            <div style="font-size:18px;font-weight:700;color:#065f46;">${parseFloat(d.total_litres).toFixed(1)}L</div>
            <div style="font-size:10px;color:#94a3b8;">Payable Litres</div>
          </div>
          <div style="text-align:center;background:#fef3c7;border-radius:12px;border:1.5px solid #fcd34d;border-radius:9px;padding:10px;">
            <div style="font-size:18px;font-weight:700;color:#f59e0b;">+KES ${parseFloat(d.bonus).toFixed(2)}</div>
            <div style="font-size:10px;color:#94a3b8;">Quality Bonus</div>
          </div>
          <div style="text-align:center;background:#dbeafe;border-radius:12px;border:1.5px solid #93c5fd">
            <div style="font-size:18px;font-weight:700;color:#1e40af;">KES ${parseFloat(d.net).toFixed(2)}</div>
            <div style="font-size:10px;color:#94a3b8;">Net Payable</div>
          </div>
        </div>
        <div style="margin-top:10px;font-size:12px;color:#94a3b8;">
          Pay to: ${d.farmer?.mpesa_number?'📱 '+d.farmer.mpesa_number:''} ${d.farmer?.bank_name?'🏦 '+d.farmer.bank_name:''}
        </div>${rejWarn}`;
    } else {
        pv.innerHTML=`<p style="color:#f87171;font-size:13px;padding:8px 0;">⚠️ No payable deliveries found for this period.${d.rejected_count>0?' ('+d.rejected_count+' rejected deliveries cannot be paid)':''}</p>`;
    }
}

async function submitPayment(){
    const form=document.getElementById('payForm');
    const data=new FormData(form);
    const btn=document.getElementById('payBtn');
    btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Processing...';
    try{
        const r=await fetch('../php/process_payment.php',{method:'POST',body:data});
        const d=await r.json();
        if(d.success){closeModal('payModal');toast('Payment KES '+parseFloat(d.amount).toFixed(2)+' processed for '+d.litres+'L','s');form.reset();setTimeout(()=>location.reload(),2200);}
        else toast(d.message||'Payment failed','e');
    }catch(e){toast('Network error','e');}
    btn.disabled=false;btn.innerHTML='<i class="fas fa-check"></i> Process Payment';
}

async function runAI(){
    const btn=document.getElementById('ai-btn');
    btn.disabled=true;btn.innerHTML='<i class="fas fa-circle-notch fa-spin"></i> Analyzing...';
    const result=document.getElementById('ai-result');
    result.innerHTML=`<div style="text-align:center;padding:50px 20px;color:#94a3b8;">
        <i class="fas fa-circle-notch fa-spin" style="font-size:36px;display:block;margin-bottom:14px;color:#8b5cf6;"></i>
        AI is analyzing milk parameters...</div>`;

    const fat       = parseFloat(document.getElementById('ai-fat')?.value)||null;
    const protein   = parseFloat(document.getElementById('ai-protein')?.value)||null;
    const ph        = parseFloat(document.getElementById('ai-ph')?.value)||null;
    const snf       = parseFloat(document.getElementById('ai-snf')?.value)||null;
    const water     = parseFloat(document.getElementById('ai-water')?.value)||0;
    const temp      = parseFloat(document.getElementById('ai-temp')?.value)||null;
    const hours     = parseFloat(document.getElementById('ai-hours')?.value)||0;
    const antibiotic= document.getElementById('ai-antibiotic')?.value||'negative';
    const smell     = document.getElementById('ai-smell')?.value||'normal';
    const farmerName= document.getElementById('ai-farmer')?.value||'';

    // Compute grade locally
    let grade='—',gradeColor='#6b7280',score=100;
    if(antibiotic==='positive'){grade='Rejected';gradeColor='#f87171';score=0;}
    else if(water>15){grade='Rejected';gradeColor='#f87171';score=0;}
    else if(ph&&(ph<5.8||ph>7.2)){grade='Rejected';gradeColor='#f87171';score=0;}
    else if(fat||protein||ph){
        if(fat&&fat<3.0)score-=30; else if(fat&&fat<3.5)score-=15;
        if(protein&&protein<2.8)score-=25; else if(protein&&protein<3.0)score-=12;
        if(ph&&(ph<6.4||ph>7.0))score-=22; else if(ph&&(ph<6.6||ph>6.8))score-=10;
        if(snf&&snf<8.0)score-=20; else if(snf&&snf<8.5)score-=10;
        if(water>5)score-=20; else if(water>2)score-=8;
        score=Math.max(0,score);
        if(score>=85){grade='A';gradeColor='#00875a';}
        else if(score>=65){grade='B';gradeColor='#3b82f6';}
        else{grade='C';gradeColor='#f59e0b';}
    }

    // Compute spoilage
    let spScore=0,spoilage='Low',spoilageColor='#00875a';
    if(temp!==null){if(temp>10)spScore+=90;else if(temp>8)spScore+=65;else if(temp>6)spScore+=40;else spScore+=10;}
    if(smell==='sour')spScore+=35; else if(smell==='bad')spScore+=60; else if(smell==='slightly_off')spScore+=15;
    if(hours>24)spScore+=30; else if(hours>12)spScore+=15; else if(hours>8)spScore+=8;
    spScore=Math.min(100,spScore);
    if(spScore>=70){spoilage='Critical';spoilageColor='#f87171';}
    else if(spScore>=45){spoilage='High';spoilageColor='#f97316';}
    else if(spScore>=20){spoilage='Medium';spoilageColor='#d97706';}

    let safeHours='—';
    if(temp!==null){if(temp<=4)safeHours='20–24h';else if(temp<=6)safeHours='12–18h';else if(temp<=8)safeHours='6–10h';else safeHours='<4h';}

    const payload={farmer_name:farmerName,fat,protein,ph,snf,water,temperature:temp,storage_hours:hours,antibiotic,smell};
    try{
        const r=await fetch('../php/ai_analyze.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});
        const d=await r.json();
        const analysis=d.success?d.analysis:'AI service unavailable — showing calculated results.';
        const lines=analysis.split('\n').filter(l=>l.trim());
        const verdictLine=lines.find(l=>l.toLowerCase().includes('verdict')||l.toLowerCase().includes('quality'))||lines[0]||'';
        const recLines=lines.filter(l=>/^[•\-\*\d]/.test(l.trim())||l.toLowerCase().includes('recommend'));
        const storageNote=lines.find(l=>l.toLowerCase().includes('storage')||l.toLowerCase().includes('store'))||'';
        const farmerNote=lines.find(l=>l.toLowerCase().includes('farmer')||l.toLowerCase().includes('feed'))||'';
        const checks=[
            {label:'Temperature',ok:temp===null||(temp<=6),text:temp!==null?(temp<=6?'Within ideal range of 0–6°C':(temp<=8?'Slightly above ideal — monitor closely':'Above ideal — spoilage risk elevated')):'Not provided'},
            {label:'Fat Content',ok:fat!==null&&fat>=3.5,text:fat!==null?(fat>=3.5?'Meets Grade A requirement (≥3.5%)':(fat>=3.0?'Below Grade A threshold':'Low fat content')):'Not provided'},
            {label:'Protein',ok:protein!==null&&protein>=3.0,text:protein!==null?(protein>=3.0?'Meets minimum requirement of 3.0%':(protein>=2.8?'Slightly below optimal':'Low protein content')):'Not provided'},
            {label:'pH',ok:ph!==null&&ph>=6.6&&ph<=6.8,text:ph!==null?(ph>=6.6&&ph<=6.8?'Within ideal range of 6.6–6.8':(ph>=6.4&&ph<=7.0?'Slightly off ideal range':'pH out of optimal range')):'Not provided'},
            {label:'Water',ok:water<=2,text:water<=2?'No adulteration detected':(water<=5?'Slight water content detected':'High water content — possible adulteration')},
            {label:'Antibiotic',ok:antibiotic==='negative',text:antibiotic==='negative'?'Negative — safe for consumption':'⚠️ Positive — milk must be rejected'},
            {label:'Smell',ok:smell==='normal',text:smell==='normal'?'Normal smell — no off-odours':(smell==='slightly_off'?'Slight odour detected — monitor':'Strong off-odour — spoilage risk')},
        ];
        const checkHTML=checks.map(c=>`<div class="ai-check-item">
            <span class="ai-check-icon" style="color:${c.ok?'#00875a':'#f87171'};">${c.ok?'✅':'❌'}</span>
            <span><strong style="color:#0f172a;">${c.label}:</strong> ${c.text}</span></div>`).join('');
        result.innerHTML=`
        <div class="ai-summary-grid">
            <div class="ai-summary-box">
                <div class="label">Quality Grade</div>
                <div style="font-size:30px;font-weight:900;color:${gradeColor};margin-top:6px;">${grade}</div>
                <div class="sublabel">Quality Grade</div>
            </div>
            <div class="ai-summary-box">
                <div class="label">Spoilage Risk</div>
                <div class="value" style="color:${spoilageColor};margin-top:8px;font-size:18px;">${spoilage.toUpperCase()}</div>
                <div class="sublabel">Spoilage Risk</div>
            </div>
            <div class="ai-summary-box">
                <div class="label">Score /100</div>
                <div class="value" style="color:${gradeColor};margin-top:8px;">${grade==='Rejected'?0:score}</div>
                <div class="sublabel">Quality Score</div>
            </div>
            <div class="ai-summary-box">
                <div class="label">Safe Storage</div>
                <div class="value" style="color:#3b82f6;font-size:18px;margin-top:8px;">${safeHours}</div>
                <div class="sublabel">Est. Safe Time</div>
            </div>
        </div>
        <div class="ai-analysis-body">
            ${verdictLine?`<div class="ai-verdict-box" style="background:#fffbeb;border-left:4px solid #f59e0b;border-radius:10px;padding:14px 16px;margin-bottom:14px;">
                <p style="color:#92400e;">🐄 ${verdictLine.replace(/\*\*/g,'')}</p></div>`:''}
            <div class="ai-checklist">${checkHTML}</div>
            ${recLines.length?`<div class="ai-section-title" style="font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:#00875a;margin:18px 0 10px;padding:8px 12px;background:#eaf7f2;border-radius:8px;border-left:4px solid #00875a;">📋 RECOMMENDATIONS</div>
            <div style="font-size:14px;color:#0d1f2d;line-height:1.9;font-weight:500;">
                ${recLines.map(l=>'• '+l.replace(/^[•\-\*\d\.]+\s*/,'')).join('<br>')}</div>`:''}
            ${farmerNote?`<div style="margin-top:14px;background:linear-gradient(90deg,#f0fdf4,#ecfdf5);border-radius:10px;padding:12px 14px;border-left:3px solid #059669;">
                <p style="font-size:14px;color:#0d1f2d;line-height:1.7;">🌾 <strong style="color:#00875a;">For Farmer:</strong> ${farmerNote.replace(/\*\*/g,'').replace(/For Farmer:/i,'')}</p></div>`:''}
            ${storageNote?`<div style="margin-top:10px;background:#eff6ff;border-radius:10px;padding:12px 14px;border-left:3px solid #3b82f6;">
                <p style="font-size:14px;color:#0d1f2d;line-height:1.7;">🏭 <strong style="color:#2563eb;">Storage:</strong> ${storageNote.replace(/\*\*/g,'').replace(/Storage:/i,'')}</p></div>`:''}
        </div>`;
    }catch(e){result.innerHTML=`<div class="ai-analysis-body"><p style="color:#f87171;">Connection error — please check your internet connection.</p></div>`;}
    btn.disabled=false;btn.innerHTML='<i class="fas fa-robot"></i> Analyze with AI';
}

async function aiAnalyzeDelivery(id,name){
    showPage('ai-advisor',null);
    document.getElementById('ai-result').innerHTML=`<div style="text-align:center;padding:50px;color:#94a3b8;"><i class="fas fa-circle-notch fa-spin" style="font-size:32px;display:block;margin-bottom:12px;color:#8b5cf6;"></i>Auto-filling from delivery data...</div>`;
    try{
        // Fetch full delivery data and auto-fill all form fields
        const r=await fetch('../php/get_delivery.php?id='+id);
        const d=await r.json();
        if(d.success && d.delivery){
            const del=d.delivery;
            const setVal=(id,val)=>{const el=document.getElementById(id);if(el&&val!==null&&val!==undefined&&val!=='')el.value=val;};
            setVal('ai-farmer', del.farmer_name||name);
            setVal('ai-qty',    del.quantity_litres);
            setVal('ai-temp',   del.temperature);
            setVal('ai-fat',    del.fat_content);
            setVal('ai-protein',del.protein_content);
            setVal('ai-ph',     del.acidity);
            setVal('ai-snf',    del.snf);
            setVal('ai-water',  del.water_content);
            setVal('ai-hours',  del.storage_hours||del.hours_stored||0);
            if(del.antibiotic_test){
                const ab=document.getElementById('ai-antibiotic');
                if(ab) ab.value = del.antibiotic_test.toLowerCase().includes('neg')?'negative':'positive';
            }
            // Run AI analysis automatically with the filled data
            document.getElementById('ai-result').innerHTML=`<div style="text-align:center;padding:20px;color:#059669;"><i class="fas fa-check-circle" style="font-size:24px;display:block;margin-bottom:8px;"></i><strong>Fields auto-filled from delivery!</strong><br><small style="color:#64748b;">Running AI analysis...</small></div>`;
            setTimeout(()=>runAI(), 600);
        } else {
            document.getElementById('ai-farmer').value=name;
            document.getElementById('ai-result').innerHTML=`<div style="text-align:center;padding:30px;color:#f59e0b;"><i class="fas fa-exclamation-triangle" style="font-size:24px;display:block;margin-bottom:8px;"></i>Could not load delivery data. Fill fields manually and click Analyze.</div>`;
        }
    }catch(e){
        document.getElementById('ai-farmer').value=name;
        document.getElementById('ai-result').innerHTML=`<div style="text-align:center;padding:30px;color:#f59e0b;">Could not auto-fill. Please enter values manually.</div>`;
    }
}

async function submitStaff(){
    const form=document.getElementById('staffForm');const data=new FormData(form);
    const r=await fetch('../php/add_staff.php',{method:'POST',body:data});const d=await r.json();
    if(d.success){closeModal('staffModal');showCredentials(d.staff_id,d.temp_password,'Staff');form.reset();setTimeout(()=>location.reload(),3500);}else toast(d.message||'Failed','e');
}
async function submitAnnouncement(){
    const form=document.getElementById('annForm');const data=new FormData(form);
    const r=await fetch('../php/add_announcement.php',{method:'POST',body:data});const d=await r.json();
    if(d.success){closeModal('annModal');toast('Announcement posted!','s');form.reset();setTimeout(()=>location.reload(),2000);}else toast(d.message||'Failed','e');
}
async function submitTank(){
    const form=document.getElementById('tankForm');const data=new FormData(form);
    const r=await fetch('../php/update_tank.php',{method:'POST',body:data});const d=await r.json();
    if(d.success){closeModal('tankModal');toast('Tank updated!','s');setTimeout(()=>location.reload(),1500);}else toast(d.message||'Failed','e');
}
async function submitPrices(){
    const form=document.getElementById('priceForm');const data=new FormData(form);
    const r=await fetch('../php/update_price.php',{method:'POST',body:data});const d=await r.json();
    if(d.success)toast('Prices updated!','s');else toast(d.message||'Failed','e');
}
function openTankModal(id,name,vol,temp,status,notes){
    document.getElementById('tank-id').value=id;
    document.getElementById('tank-name-disp').textContent=name;
    document.getElementById('tank-vol').value=vol;
    document.getElementById('tank-temp').value=temp;
    document.getElementById('tank-status').value=status;
    document.getElementById('tank-notes').value=notes;
    openModal('tankModal');
}
function openDeliveryForFarmer(id,name){document.getElementById('dlv-farmer').value=id;openModal('dlvModal');}
async function generateReport(){
    const type   = document.getElementById('rpt-type').value;
    const from   = document.getElementById('rpt-from').value;
    const to     = document.getElementById('rpt-to').value;
    const farmer = document.getElementById('rpt-farmer').value;
    const farmerName = document.getElementById('rpt-farmer').options[document.getElementById('rpt-farmer').selectedIndex].text;
    const res    = document.getElementById('rpt-result');

    res.innerHTML = `<div style="text-align:center;padding:40px;color:#94a3b8;">
        <i class="fas fa-circle-notch fa-spin" style="font-size:32px;display:block;margin-bottom:12px;color:rgba(16,217,126,0.4);"></i>
        Generating report...</div>`;

    try {
        const r = await fetch(`../php/generate_report.php?type=${type}&from=${from}&to=${to}&farmer=${farmer}`);
        const d = await r.json();
        if(!d.success){ res.innerHTML=`<p style="color:#f87171;padding:20px;">${d.message}</p>`; return; }

        const s = d.stats;
        const typeLabel = {deliveries:'Delivery',quality:'Quality',spoilage:'Spoilage',payment:'Payment'}[type]||type;
        const farmerLabel = farmer ? farmerName : 'All Farmers';

        // Format date nicely
        const fmtDate = dt => {
            const d = new Date(dt); if(isNaN(d)) return dt;
            return d.toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'});
        };

        // Grade badge colours
        const gradeBadge = g => {
            if(!g) return '—';
            const cfg = {A:['rgba(16,217,126,0.15)','#00875a'],B:['#dbeafe','#1e40af'],C:['rgba(240,165,0,0.15)','#f59e0b'],rejected:['#fee2e2','#991b1b']};
            const c = cfg[g]||['rgba(255,255,255,0.06)','rgba(255,255,255,0.5)'];
            return `<span style="background:${c[0]};color:${c[1]};padding:3px 10px;border-radius:50px;font-size:11px;font-weight:700;">Grade ${g.charAt(0).toUpperCase()+g.slice(1)}</span>`;
        };

        const statusBadge = s => {
            const cfg = {paid:['rgba(16,217,126,0.15)','#00875a'],pending:['rgba(240,165,0,0.15)','#f59e0b'],processing:['#dbeafe','#1e40af'],completed:['rgba(16,217,126,0.15)','#00875a'],failed:['#fee2e2','#dc2626']};
            const c = cfg[s]||['rgba(255,255,255,0.06)','rgba(255,255,255,0.5)'];
            return `<span style="background:${c[0]};color:${c[1]};padding:3px 10px;border-radius:50px;font-size:11px;font-weight:700;">${s.charAt(0).toUpperCase()+s.slice(1)}</span>`;
        };

        const spoilageBadge = r => {
            const cfg = {low:['rgba(16,217,126,0.15)','#00875a'],medium:['rgba(240,165,0,0.15)','#f59e0b'],high:['#ffedd5','#c2410c'],critical:['#fee2e2','#dc2626']};
            const c = cfg[r]||['rgba(255,255,255,0.06)','rgba(255,255,255,0.5)'];
            return `<span style="background:${c[0]};color:${c[1]};padding:3px 10px;border-radius:50px;font-size:11px;font-weight:700;">${r.charAt(0).toUpperCase()+r.slice(1)}</span>`;
        };

        // Build table based on report type
        let tableHTML = '';
        if(type === 'deliveries'){
            const rows = d.rows.map(row=>`<tr>
                <td style="padding:12px 14px;font-size:13px;text-align:left;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${row.full_name}</td>
                <td style="padding:12px 14px;font-size:13px;text-align:left;">${fmtDate(row.delivery_date)}</td>
                <td style="padding:12px 14px;font-size:13px;text-align:right;">${parseFloat(row.quantity_litres).toFixed(1)}L</td>
                <td style="padding:12px 14px;text-align:center;">${gradeBadge(row.quality_grade)}</td>
                <td style="padding:12px 14px;font-size:13px;text-align:right;">KES ${parseFloat(row.total_amount).toFixed(2)}</td>
                <td style="padding:12px 14px;text-align:center;">${statusBadge(row.payment_status)}</td>
            </tr>`).join('');
            tableHTML = `<table style="width:100%;border-collapse:collapse;table-layout:fixed;">
                <colgroup><col style="width:22%"><col style="width:14%"><col style="width:12%"><col style="width:12%"><col style="width:18%"><col style="width:22%"></colgroup>
                <thead><tr style="background:linear-gradient(90deg,#f0fdf4,#ecfdf5);">
                    <th style="padding:12px 14px;text-align:left;font-size:11px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.5px;">Farmer</th>
                    <th style="padding:12px 14px;text-align:left;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;letter-spacing:.5px;">Date</th>
                    <th style="padding:12px 14px;text-align:right;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;letter-spacing:.5px;">Quantity</th>
                    <th style="padding:12px 14px;text-align:center;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;letter-spacing:.5px;">Grade</th>
                    <th style="padding:12px 14px;text-align:right;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;letter-spacing:.5px;">Amount</th>
                    <th style="padding:12px 14px;text-align:center;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;letter-spacing:.5px;">Payment Status</th>
                </tr></thead>
                <tbody>${rows||'<tr><td colspan="6" style="text-align:center;padding:30px;color:#94a3b8;">No deliveries found for this period</td></tr>'}</tbody>
            </table>`;
        } else if(type === 'quality'){
            const rows = d.rows.map(row=>`<tr>
                <td style="padding:12px 14px;font-size:13px;text-align:left;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${row.full_name}</td>
                <td style="padding:12px 14px;font-size:13px;text-align:left;">${fmtDate(row.delivery_date)}</td>
                <td style="padding:12px 14px;font-size:13px;text-align:right;">${parseFloat(row.quantity_litres).toFixed(1)}L</td>
                <td style="padding:12px 14px;font-size:13px;text-align:right;color:${row.fat_content>=3.5?'#00875a':row.fat_content>=3.0?'#f59e0b':'#dc2626'}">${row.fat_content!==null?row.fat_content+'%':'—'}</td>
                <td style="padding:12px 14px;font-size:13px;text-align:right;color:${(row.acidity>=6.6&&row.acidity<=6.8)?'#00875a':((row.acidity>=6.4&&row.acidity<=7.0)?'#f59e0b':'#dc2626')}">${row.acidity||'—'}</td>
                <td style="padding:12px 14px;text-align:center;">${gradeBadge(row.quality_grade)}</td>
                <td style="padding:12px 14px;font-size:13px;text-align:right;">${parseFloat(row.total_amount).toFixed(2)}</td>
            </tr>`).join('');
            tableHTML = `<table style="width:100%;border-collapse:collapse;table-layout:fixed;">
                <colgroup><col style="width:22%"><col style="width:13%"><col style="width:11%"><col style="width:10%"><col style="width:9%"><col style="width:12%"><col style="width:23%"></colgroup>
                <thead><tr style="background:linear-gradient(90deg,#f0fdf4,#ecfdf5);">
                    <th style="padding:12px 14px;text-align:left;font-size:11px;font-weight:700;color:#374151;text-transform:uppercase;">Farmer</th>
                    <th style="padding:12px 14px;text-align:left;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Date</th>
                    <th style="padding:12px 14px;text-align:right;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Quantity</th>
                    <th style="padding:12px 14px;text-align:right;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Fat %</th>
                    <th style="padding:12px 14px;text-align:right;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">pH</th>
                    <th style="padding:12px 14px;text-align:center;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Grade</th>
                    <th style="padding:12px 14px;text-align:right;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Amount (KES)</th>
                </tr></thead>
                <tbody>${rows||'<tr><td colspan="7" style="text-align:center;padding:30px;color:#94a3b8;">No data found</td></tr>'}</tbody>
            </table>`;
        } else if(type === 'spoilage'){
            const rows = d.rows.map(row=>`<tr>
                <td style="padding:12px 14px;font-size:13px;text-align:left;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${row.full_name}</td>
                <td style="padding:12px 14px;font-size:13px;text-align:left;">${fmtDate(row.delivery_date)}</td>
                <td style="padding:12px 14px;font-size:13px;text-align:right;">${parseFloat(row.quantity_litres).toFixed(1)}L</td>
                <td style="padding:12px 14px;font-size:13px;font-weight:700;text-align:right;color:${row.temperature>8?'#dc2626':row.temperature>6?'#d97706':'#00875a'}">${row.temperature!==null?row.temperature+'&deg;C':'—'}</td>
                <td style="padding:12px 14px;text-align:center;">${spoilageBadge(row.spoilage_risk)}</td>
                <td style="padding:12px 14px;text-align:center;">${gradeBadge(row.quality_grade)}</td>
            </tr>`).join('');
            tableHTML = `<table style="width:100%;border-collapse:collapse;table-layout:fixed;">
                <colgroup><col style="width:25%"><col style="width:15%"><col style="width:13%"><col style="width:15%"><col style="width:17%"><col style="width:15%"></colgroup>
                <thead><tr style="background:#fff1f2;">
                    <th style="padding:12px 14px;text-align:left;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Farmer</th>
                    <th style="padding:12px 14px;text-align:left;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Date</th>
                    <th style="padding:12px 14px;text-align:right;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Quantity</th>
                    <th style="padding:12px 14px;text-align:right;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Temperature</th>
                    <th style="padding:12px 14px;text-align:center;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Spoilage Risk</th>
                    <th style="padding:12px 14px;text-align:center;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Quality Grade</th>
                </tr></thead>
                <tbody>${rows||'<tr><td colspan="6" style="text-align:center;padding:30px;color:#94a3b8;">No data found</td></tr>'}</tbody>
            </table>`;
        } else if(type === 'payment'){
            const rows = d.payments.map(row=>`<tr>
                <td style="padding:12px 14px;font-size:13px;text-align:left;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${row.full_name}</td>
                <td style="padding:12px 14px;font-size:11px;text-align:left;">${fmtDate(row.payment_period_start)} &ndash; ${fmtDate(row.payment_period_end)}</td>
                <td style="padding:12px 14px;font-size:13px;text-align:right;">${parseFloat(row.total_litres).toFixed(1)}L</td>
                <td style="padding:12px 14px;font-size:13px;text-align:right;">${parseFloat(row.base_amount).toFixed(2)}</td>
                <td style="padding:12px 14px;font-size:13px;text-align:right;color:#f59e0b;">+${parseFloat(row.quality_bonus).toFixed(2)}</td>
                <td style="padding:12px 14px;font-size:13px;font-weight:700;text-align:right;color:#059669;">${parseFloat(row.net_amount).toFixed(2)}</td>
                <td style="padding:12px 14px;text-align:center;">${statusBadge(row.payment_status)}</td>
                <td style="padding:12px 14px;font-size:12px;text-align:left;color:#9ca3af;">${fmtDate(row.payment_date)}</td>
            </tr>`).join('');
            tableHTML = `<table style="width:100%;border-collapse:collapse;table-layout:fixed;">
                <colgroup><col style="width:18%"><col style="width:19%"><col style="width:9%"><col style="width:13%"><col style="width:13%"><col style="width:13%"><col style="width:9%"><col style="width:12%"></colgroup>
                <thead><tr style="background:#fffbeb;">
                    <th style="padding:12px 14px;text-align:left;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Farmer</th>
                    <th style="padding:12px 14px;text-align:left;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Period</th>
                    <th style="padding:12px 14px;text-align:right;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Litres</th>
                    <th style="padding:12px 14px;text-align:right;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Base (KES)</th>
                    <th style="padding:12px 14px;text-align:right;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Bonus (KES)</th>
                    <th style="padding:12px 14px;text-align:right;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Net Paid (KES)</th>
                    <th style="padding:12px 14px;text-align:center;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Status</th>
                    <th style="padding:12px 14px;text-align:left;font-size:11px;font-weight:700;color:rgba(15,23,42,0.55);text-transform:uppercase;">Pay Date</th>
                </tr></thead>
                <tbody>${rows||'<tr><td colspan="8" style="text-align:center;padding:30px;color:#94a3b8;">No payments found</td></tr>'}</tbody>
            </table>`;
        }

        res.innerHTML = `
        <div style="background:white;border-radius:18px;border:1px solid #e2eaed;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

          <!-- Report Header -->
          <div style="padding:22px 28px;background:linear-gradient(135deg,#003d20 0%,#00875a 100%);display:flex;justify-content:space-between;align-items:center;">
            <div>
              <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:#ffffff;margin-bottom:4px;">${typeLabel} Report</h3>
              <p style="font-size:13px;color:rgba(255,255,255,0.75);">${fmtDate(from)} &ndash; ${fmtDate(to)} &nbsp;&middot;&nbsp; ${farmerLabel}</p>
            </div>
            <button onclick="window.print()" style="background:rgba(255,255,255,0.15);color:white;border:1.5px solid rgba(255,255,255,0.4);padding:9px 20px;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:8px;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'"><i class="fas fa-print"></i> Print</button>
          </div>

          <!-- Summary Stats -->
          <div style="display:grid;grid-template-columns:repeat(4,1fr);background:#f8fafc;border-bottom:2px solid #e2eaed;">
            <div style="padding:22px;text-align:center;border-right:1px solid #e2eaed;">
              <div style="font-family:'Playfair Display',serif;font-size:32px;font-weight:700;color:#00875a;">${parseFloat(s.total_litres).toFixed(0)}L</div>
              <div style="font-size:11px;color:#7a93a6;margin-top:5px;text-transform:uppercase;letter-spacing:.6px;font-weight:700;">Total Litres</div>
            </div>
            <div style="padding:22px;text-align:center;border-right:1px solid #e2eaed;">
              <div style="font-family:'Playfair Display',serif;font-size:32px;font-weight:700;color:#065f46;">${s.grade_a}</div>
              <div style="font-size:11px;color:#7a93a6;margin-top:5px;text-transform:uppercase;letter-spacing:.6px;font-weight:700;">Grade A Deliveries</div>
            </div>
            <div style="padding:22px;text-align:center;border-right:1px solid #e2eaed;">
              <div style="font-family:'Playfair Display',serif;font-size:32px;font-weight:700;color:#92400e;">KES ${(parseFloat(s.paid_amount)/1000).toFixed(1)}K</div>
              <div style="font-size:11px;color:#7a93a6;margin-top:5px;text-transform:uppercase;letter-spacing:.6px;font-weight:700;">Paid This Period</div>
            </div>
            <div style="padding:22px;text-align:center;">
              <div style="font-family:'Playfair Display',serif;font-size:32px;font-weight:700;color:#1e40af;">${s.active_farmers}</div>
              <div style="font-size:11px;color:#7a93a6;margin-top:5px;text-transform:uppercase;letter-spacing:.6px;font-weight:700;">Active Farmers</div>
            </div>
          </div>

          <!-- Grade breakdown -->
          <div style="padding:12px 28px;background:linear-gradient(90deg,#f0fdf4,#ecfdf5);border-bottom:1px solid #d1fae5;display:flex;gap:16px;flex-wrap:wrap;align-items:center;">
            <span style="font-size:12px;color:#4a6070;font-weight:700;">Grade Breakdown:</span>
            <span style="font-size:12px;color:#065f46;font-weight:700;background:#d1fae5;padding:3px 12px;border-radius:50px;">Grade A: ${s.grade_a}</span>
            <span style="font-size:12px;color:#1e40af;font-weight:700;background:#dbeafe;padding:3px 12px;border-radius:50px;">Grade B: ${s.grade_b}</span>
            <span style="font-size:12px;color:#92400e;font-weight:700;background:#fef3c7;padding:3px 12px;border-radius:50px;">Grade C: ${s.grade_c}</span>
            <span style="font-size:12px;color:#991b1b;font-weight:700;background:#fee2e2;padding:3px 12px;border-radius:50px;">Rejected: ${s.rejected}</span>
          </div>

          <!-- Table -->
          <div style="overflow-x:auto;">${tableHTML}</div>
        </div>`;

    } catch(e) {
        res.innerHTML = `<p style="color:#f87171;padding:20px;">Error generating report: ${e.message}</p>`;
    }
}

// ===== M-PESA FUNCTIONS =====
function sendMpesa(paymentId, farmerId, amount, phone, farmerName){
    document.getElementById('mpesa-pid').value   = paymentId;
    document.getElementById('mpesa-fid').value   = farmerId;
    document.getElementById('mpesa-amt').value   = amount;
    document.getElementById('mpesa-phone').value = phone;
    document.getElementById('mpesa-farmer').textContent  = farmerName;
    document.getElementById('mpesa-amount-disp').textContent = 'KES '+parseFloat(amount).toFixed(2);
    document.getElementById('mpesa-btn-amt').textContent = parseFloat(amount).toFixed(2);
    document.getElementById('mpesa-result').style.display = 'none';
    openModal('mpesaModal');
}

async function confirmMpesa(){
    const btn    = document.getElementById('mpesa-btn');
    const result = document.getElementById('mpesa-result');
    const phone  = document.getElementById('mpesa-phone').value.trim();
    const pid    = document.getElementById('mpesa-pid').value;
    const fid    = document.getElementById('mpesa-fid').value;
    const amt    = document.getElementById('mpesa-amt').value;

    if(!phone){ toast('Please enter the M-Pesa phone number','e'); return; }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    result.style.display = 'none';

    const data = new FormData();
    data.append('payment_id', pid);
    data.append('farmer_id',  fid);
    data.append('amount',     amt);
    data.append('phone',      phone);

    // Also send farmer_name for the receipt description
    data.append('farmer_name', document.getElementById('mpesa-farmer').textContent||'');
    try {
        const r = await fetch('../php/mpesa_stk.php',{method:'POST',body:data});
        const d = await r.json();
        result.style.display = 'block';
        if(d.success){
            result.style.background = '#f0fdf4';
            result.style.border     = '1px solid #bbf7d0';
            result.style.color      = '#065f46';
            result.style.borderRadius = '10px';
            result.style.padding    = '12px 14px';
            result.innerHTML = `<i class="fas fa-check-circle"></i> <strong>STK Push Sent!</strong><br>
                <span style="font-size:12px;color:#4a6070;">${d.message}</span><br>
                <small style="color:#94a3b8;">Checkout ID: ${d.checkout_id||'—'}</small>`;
            toast('M-Pesa STK Push sent to farmer phone!','s');
            setTimeout(()=>location.reload(),4000);
        } else {
            result.style.background = '#fff1f2';
            result.style.border     = '1px solid #fecdd3';
            result.style.color      = '#9f1239';
            result.style.borderRadius = '10px';
            result.style.padding    = '12px 14px';
            result.innerHTML = `<i class="fas fa-exclamation-circle"></i> <strong>Failed:</strong> ${d.message}`;
        }
    } catch(e){
        toast('Network error — check your connection','e');
    }

    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send KES <span id="mpesa-btn-amt">'+parseFloat(amt).toFixed(2)+'</span>';
}

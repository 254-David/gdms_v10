<?php
session_start();require_once '../includes/config.php';header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD']!=='POST'){echo json_encode(['success'=>false]);exit;}
$data=json_decode(file_get_contents('php://input'),true);
$prompt="You are an expert dairy milk quality analyst. Analyze these milk sample parameters and give a professional assessment.\n\n";
$prompt.="QUALITY PARAMETERS:\n";
if(isset($data['fat']))$prompt.="- Fat Content: {$data['fat']}% (ideal: >3.5%)\n";
if(isset($data['protein']))$prompt.="- Protein: {$data['protein']}% (ideal: >3.0%)\n";
if(isset($data['ph']))$prompt.="- pH: {$data['ph']} (ideal: 6.6-6.8)\n";
if(isset($data['ta']))$prompt.="- Titratable Acidity: {$data['ta']}°T (ideal: 16-18°T)\n";
if(isset($data['snf']))$prompt.="- SNF: {$data['snf']}% (ideal: >8.5%)\n";
if(isset($data['density']))$prompt.="- Density: {$data['density']} g/ml (ideal: 1.028-1.033)\n";
if(isset($data['water']))$prompt.="- Water Content: {$data['water']}% (ideal: <5%)\n";
if(isset($data['lactose']))$prompt.="- Lactose: {$data['lactose']}% (ideal: 4.5-5.0%)\n";
if(isset($data['scc']))$prompt.="- Somatic Cell Count: {$data['scc']} (low<200k, medium 200-400k, high>400k SCC/ml)\n";
if(isset($data['antibiotic']))$prompt.="- Antibiotic Test: {$data['antibiotic']}\n";
if(isset($data['alcohol']))$prompt.="- Alcohol Test: {$data['alcohol']}\n";
$prompt.="\nSPOILAGE PARAMETERS:\n";
if(isset($data['temperature']))$prompt.="- Temperature: {$data['temperature']}°C (ideal: <6°C)\n";
if(isset($data['smell']))$prompt.="- Smell: {$data['smell']}\n";
if(isset($data['visual']))$prompt.="- Visual Appearance: {$data['visual']}\n";
if(isset($data['storage_hours']))$prompt.="- Storage Hours: {$data['storage_hours']}h\n";
$prompt.="\nProvide:\n1. QUALITY VERDICT (Grade A/B/C/Rejected) based only on composition parameters\n2. SPOILAGE RISK (Low/Medium/High/Critical) based only on temperature and sensory checks\n3. KEY ISSUES found\n4. SPECIFIC RECOMMENDATIONS for improvement\n5. SAFE STORAGE TIME estimate\n\nBe concise, practical and professional. Max 200 words.";
try{
    $r=@file_get_contents(GROQ_API_URL,false,stream_context_create(['http'=>['method'=>'POST','header'=>"Content-Type: application/json\r\nAuthorization: Bearer ".GROQ_API_KEY."\r\n",'content'=>json_encode(['model'=>GROQ_MODEL,'max_tokens'=>400,'messages'=>[['role'=>'user','content'=>$prompt]]]),'timeout'=>20]]));
    if($r){$d=json_decode($r,true);$text=$d['choices'][0]['message']['content']??'AI analysis unavailable.';}
    else{$text='AI service unavailable. Based on parameters: '.(isset($data['fat'])&&$data['fat']>=3.5?'Fat OK. ':'Low fat. ').(isset($data['ph'])&&$data['ph']>=6.6&&$data['ph']<=6.8?'pH normal. ':'pH abnormal. ');}
    if(isset($data['delivery_id'])&&!empty($data['delivery_id'])){$db=Database::getInstance();$db->update("UPDATE milk_deliveries SET ai_analysis=? WHERE delivery_id=?",[$text,$data['delivery_id']]);}
    echo json_encode(['success'=>true,'analysis'=>$text]);
}catch(Exception $e){echo json_encode(['success'=>false,'message'=>$e->getMessage()]);}

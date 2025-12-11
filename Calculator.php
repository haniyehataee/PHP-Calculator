<?php
// calculator.php
$result = null;
$error = null;

// گرفتن ورودی‌ها (POST)
$left = isset($_POST['left']) ? trim($_POST['left']) : '';
$right = isset($_POST['right']) ? trim($_POST['right']) : '';
$op = isset($_POST['op']) ? $_POST['op'] : 'add';

// تابع کمکی برای تبدیل به عدد (پذیرش اعشاری)
function to_number($s) {
    // حذف فاصله‌های اضافی و جایگزینی کامای فارسی/انگلیسی
    $s = str_replace(['٬',',',' ',"\xC2\xA0"], ['','','',''], $s);
    // اجازه به منفی و نقطه و اعداد
    if ($s === '' || !preg_match('/^-?\d+(\.\d+)?$/', $s)) return null;
    return (strpos($s, '.') !== false) ? (float)$s : (float)$s;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a = to_number($left);
    $b = to_number($right);

    if ($a === null || $b === null) {
        $error = "لطفا دو عدد معتبر وارد کنید (عدد صحیح یا اعشاری).";
    } else {
        switch ($op) {
            case 'add':
                $result = $a + $b;
                break;
            case 'sub':
                $result = $a - $b;
                break;
            case 'mul':
                $result = $a * $b;
                break;
            case 'div':
                if ($b == 0) $error = "تقسیم بر صفر ممکن نیست.";
                else $result = $a / $b;
                break;
            case 'pow':
                $result = pow($a, $b);
                break;
            case 'mod':
                if ((int)$b == 0) $error = "محاسبه با مودولی صفر ممکن نیست.";
                else $result = fmod($a, $b);
                break;
            default:
                $error = "عملیات نامعتبر.";
        }
    }
}
?>
<!doctype html>
<html lang="fa">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ماشین‌حساب ساده (PHP)</title>
<style>
    body{font-family: "Tahoma", sans-serif; background:#f3f6fb; color:#111; padding:30px;}
    .card{max-width:520px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,0.08);padding:22px;}
    h1{font-size:20px;margin:0 0 12px;text-align:center;}
    .row{display:flex;gap:10px;margin-bottom:12px;}
    input[type="text"]{flex:1;padding:10px;border:1px solid #d7dbe6;border-radius:8px;font-size:16px;}
    select{padding:10px;border-radius:8px;border:1px solid #d7dbe6;font-size:16px;}
    button{padding:10px 14px;border-radius:8px;border:0;background:#2563eb;color:#fff;font-weight:600;cursor:pointer;}
    .result{background:#f8fafc;border:1px dashed #cfe0ff;padding:12px;border-radius:8px;margin-top:12px;font-weight:700;text-align:center;}
    .error{background:#fff1f0;border:1px solid #ffcccc;color:#a00;padding:10px;border-radius:8px;text-align:center;margin-top:12px;}
    .hint{font-size:13px;color:#666;margin-top:8px;text-align:center;}
    .ops{display:flex;gap:8px;flex-wrap:wrap;}
    .ops button{flex:1;background:#0ea5a0;}
</style>
</head>
<body>
<div class="card">
  <h1>ماشین‌حساب ساده — PHP</h1>

  <form method="post" action="">
    <div class="row">
      <input type="text" name="left" placeholder="عدد اول" value="<?php echo htmlspecialchars($left); ?>" />
      <input type="text" name="right" placeholder="عدد دوم" value="<?php echo htmlspecialchars($right); ?>" />
    </div>

    <div class="row">
      <select name="op" aria-label="عملیات">
        <option value="add" <?php if($op==='add') echo 'selected'; ?>>جمع (+)</option>
        <option value="sub" <?php if($op==='sub') echo 'selected'; ?>>تفریق (−)</option>
        <option value="mul" <?php if($op==='mul') echo 'selected'; ?>>ضرب (×)</option>
        <option value="div" <?php if($op==='div') echo 'selected'; ?>>تقسیم (÷)</option>
        <option value="pow" <?php if($op==='pow') echo 'selected'; ?>>توان</option>
        <option value="mod" <?php if($op==='mod') echo 'selected'; ?>>باقی‌مانده (mod)</option>
      </select>
      <button type="submit">محاسبه</button>
    </div>
  </form>
  <?php if ($error): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php elseif ($result !== null): ?>
    <div class="result">نتیجه: <?php echo htmlspecialchars((string)$result); ?></div>
  <?php endif; ?>

  <div class="hint">
    ورودی‌ها می‌توانند عدد صحیح یا اعشاری باشند (مثال: 12 یا 3.14). کاما یا فاصله حذف می‌شوند.
  </div>
</div>
</body>
</html>
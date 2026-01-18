<?php
// proxy-login.php
header('Content-Type: text/html; charset=utf-8');

$username = 'mochna11';
$password = '543792';
$video_url = 'https://course.itminanpublications.com/courses/class-nursery-video-course/lessons/nursari-bangla-01/?page_tab=overview';
$login_url = 'https://course.itminanpublications.com/wp-login.php';

// লগইন POST ডাটা
$post_data = http_build_query([
    'log' => $username,
    'pwd' => $password,
    'wp-submit' => 'Log In',
    'redirect_to' => $video_url,
    'rememberme' => 'forever'
]);

// cURL সেটাপ
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $login_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

// লগইন রিকুয়েস্ট পাঠান
$response = curl_exec($ch);
curl_close($ch);

// যদি লগইন সফল হয়, ভিডিও পেজে রিডাইরেক্ট
if (strpos($response, 'Location:') !== false || strpos($response, 'login_error') === false) {
    // কুকি সেভ করে ভিডিও পেজে রিডাইরেক্ট
    echo '<script>
        localStorage.setItem("auto_login_done", "true");
        localStorage.setItem("login_time", "' . time() . '");
        window.location.href = "' . $video_url . '";
    </script>';
    echo '<h2>লগইন সফল! ভিডিও পেজে নিয়ে যাচ্ছি...</h2>';
    echo '<meta http-equiv="refresh" content="2;url=' . $video_url . '">';
} else {
    // ব্যাকআপ মেথড
    echo '<script>
        function autoFillAndSubmit() {
            // পেজের সব ইনপুট ফিল্ড খুঁজে বের করা
            var inputs = document.getElementsByTagName("input");
            for(var i = 0; i < inputs.length; i++) {
                if(inputs[i].type === "text" || inputs[i].type === "email") {
                    inputs[i].value = "' . $username . '";
                    inputs[i].dispatchEvent(new Event("input", {bubbles: true}));
                }
                if(inputs[i].type === "password") {
                    inputs[i].value = "' . $password . '";
                    inputs[i].dispatchEvent(new Event("input", {bubbles: true}));
                }
            }
            
            // ২ সেকেন্ড পর ফর্ম সাবমিট
            setTimeout(function() {
                var forms = document.getElementsByTagName("form");
                if(forms.length > 0) {
                    forms[0].submit();
                } else {
                    var buttons = document.querySelectorAll("button, input[type=submit]");
                    if(buttons.length > 0) buttons[0].click();
                }
            }, 2000);
        }
        
        // পেজ লোড হলে অটোফিল চালু
        window.onload = autoFillAndSubmit;
    </script>';
    
    // আসল লগইন পেজ লোড করান
    echo file_get_contents($login_url . '?redirect_to=' . urlencode($video_url));
}
?>
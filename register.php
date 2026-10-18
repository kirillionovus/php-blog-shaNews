<?php
require_once('config/db.php');

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(!empty($username) && !empty($email) && !empty($password)){
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if($stmt->fetch()){
            $message = 'This email is already taken!';
        }else{
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if($stmt->fetch()){
                $message = 'This username is already taken!';
            }else{
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                if($stmt->execute([$username, $email, $hashedPassword])){
                    $message = 'Registration was successful! You can now log in.';
                }else{
                    $message = 'Error during registration.';
                }
            }
        }
    }else{
        $message = 'Fill in all the fields!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$haNews | Registration</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="search.js" defer></script>
    <style type="text/tailwindcss">
    @theme {
        --color-clifford: #da373d;
    }
    </style>
</head>
<body class="min-h-screen overflow-hidden bg-slate-100 text-slate-950 selection:bg-blue-200">
<header class="relative z-20 flex h-18 items-center justify-between border-b border-slate-200 bg-white px-5 sm:px-8 lg:px-12">
    <div class="flex items-center flex-1">
        <a href="index.php" class="flex items-center gap-2 px-2 py-2 text-lm font-semibold text-slate-600 transition hover:text-slate-950 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 select-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span class="hidden sm:inline">HOMEPAGE</span>
        </a>
    </div>

    <div class="flex justify-center flex-1">
        <a class="flex items-center gap-2.5 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-blue-700 cursor-default select-none">
            <span class="text-lg font-extrabold tracking-[-0.03em] text-slate-950">$HANEWS</span>
        </a>
    </div>

    <div class="flex items-center justify-end flex-1 gap-4">
        <div id="searchBar" class="hidden items-center bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 transition-all duration-300 w-48 sm:w-64">
            <form action="index.php" method="GET" class="max-w-xl mx-auto flex items-center gap-3">
                <input id="searchInput" type="text" name="search" placeholder="Enter your query..." class="w-full bg-transparent text-slate-800 text-sm focus:outline-none py-1" autocomplete="off">
                <button id="closeSearchBtn" type="button" class="text-slate-400 hover:text-slate-950 text-xs uppercase font-medium px-2 py-1 rounded hover:bg-slate-100 transition">ESC</button>
            </form>
        </div>
        <button id="searchToggleBtn" type="button" class="p-2 text-slate-600 hover:text-slate-950 rounded-lg hover:bg-slate-100 transition focus-visible:outline-2 focus-visible:outline-blue-700 cursor-pointer" title="Поиск">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </button>

        <a href="login.php" class="p-2 text-slate-600 hover:text-slate-950 border border-slate-200 rounded-lg hover:border-slate-400 transition focus-visible:outline-2 focus-visible:outline-blue-700" title="Профиль">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </a>
    </div>
</header>



<section class="relative flex min-h-[calc(100vh-4.5rem)] items-center justify-center px-5 py-12">
    <div class="relative z-10 w-full max-w-[406px] border border-slate-300 bg-white p-6 shadow-[0_12px_35px_rgba(15,23,42,0.06)] sm:p-9">
        <div class="mb-8 text-center">
            <span class="mb-3 text-xs font-bold uppercase tracking-[0.18em] text-blue-700">Registration</span>
            <h1 class="text-3xl font-extrabold tracking-[-0.04em]">Create account</h1>
            <p class="mt-2 text-sm text-slate-500">Fill in the details to create your profile.</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="mb-6 rounded-xl border p-4 text-sm font-medium transition-all duration-200 <?php echo (strpos($message, 'successful') !== false || strpos($message, 'successfull') !== false) ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-rose-200 bg-rose-50 text-rose-800'; ?>">
            <div class="flex items-center gap-2"><?php if (strpos($message, 'successful') !== false || strpos($message, 'successful') !== false): ?><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5 shrink-0 text-emerald-600">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg><?php else: ?><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5 shrink-0 text-rose-600">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z"/></svg><?php endif; ?><span><?php echo htmlspecialchars($message); ?></span></div></div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="space-y-5">
            <div>
                <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Username</label>
                <div class="mt-2 relative">
                    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" placeholder="Your username" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 transition-all focus:border-slate-950 focus:outline-none focus:ring-1 focus:ring-slate-950">
                </div>
            </div>
            
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Email</label>
                <div class="mt-2 relative">
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" placeholder="name@example.com" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 transition-all focus:border-slate-950 focus:outline-none focus:ring-1 focus:ring-slate-950">
                </div>
            </div>
            
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Password</label>
                <div class="mt-2 relative">
                    <input type="password" id="password" name="password" placeholder="Minimum 8 characters" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 transition-all focus:border-slate-950 focus:outline-none focus:ring-1 focus:ring-slate-950 pr-10">
                    <button type="button" id="togglePasswordBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition" title="Show / hide password"><svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3.5 text-sm font-semibold text-white shadow-md transition hover:bg-slate-800 active:scale-[0.99] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-950">
                <span>Register</span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </button>
        </form>

        <p class="mt-8 text-center text-sm font-medium text-slate-500">Already have an account? <a href="login.php" class="font-bold text-slate-950 hover:underline">Log in</a></p>
    </div>
</section>

<script>
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');

    togglePasswordBtn.addEventListener('click', ()=>{
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        
        togglePasswordBtn.classList.toggle('text-slate-950', isPassword);
        togglePasswordBtn.classList.toggle('text-slate-400', !isPassword);
    });
</script>
</body>
</html>
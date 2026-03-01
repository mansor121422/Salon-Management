<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            purple: '#5A2C76', 
                            dark: '#2C0A4B',   
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    <header class="bg-gradient-to-r from-brand-purple to-brand-dark text-white pt-6 pb-20 md:pb-32 px-4 md:px-10">
        <div class="max-w-7xl mx-auto flex items-center justify-between">

            <div class="flex items-center space-x-2">
                <div class="flex space-x-0.5">
                    <span class="w-4 h-4 rounded-full border-2 border-white inline-block"></span>
                    <span class="w-4 h-4 rounded-full border-2 border-white inline-block"></span>
                </div>
                <h1 class="text-xl md:text-2xl font-bold tracking-tight uppercase">Salon</h1>
            </div>

            <nav class="hidden md:flex items-center space-x-8 text-sm font-medium">
                <a href="#" class="hover:text-gray-200">Home</a>
                <a href="#" class="hover:text-gray-200">About</a>
                <a href="#" class="hover:text-gray-200">Contact</a>
            </nav>

        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 -mt-16 md:-mt-24 pb-16">

        <div class="bg-white p-8 md:p-12 rounded-3xl shadow-2xl max-w-lg mx-auto">

            <h2 class="text-3xl md:text-4xl font-extrabold text-brand-dark text-center mb-10">Login</h2>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="text-red-500 text-center mb-4">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form class="space-y-6" action="<?= base_url('login') ?>" method="post">
                <?= csrf_field() ?>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                    <input type="text" id="username" name="username" placeholder="" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-brand-purple focus:border-brand-purple text-gray-900 placeholder-gray-400 focus:outline-none transition" autofocus>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-brand-purple focus:border-brand-purple text-gray-900 placeholder-gray-400 focus:outline-none transition">
                </div>

                <div class="text-right">
                    <a href="#" class="text-sm text-brand-purple hover:text-brand-dark transition">Forgot Password?</a>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3.5 px-6 border border-transparent rounded-xl shadow-md text-base font-semibold text-white bg-gradient-to-r from-brand-purple to-brand-dark hover:from-brand-dark hover:to-brand-purple focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-purple transition duration-200">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="text-center text-sm text-gray-500 py-8">
        © 2024 My Salon Manager. All rights reserved.
    </footer>

</body>
</html>

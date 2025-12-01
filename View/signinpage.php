
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WellMate - Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Konfigurasi khusus Tailwind
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Mendefinisikan warna utama WellMate dari gambar
                        'wellmate-blue': '#4c86e0', // Biru utama
                        'wellmate-light-blue': '#e1f0ff', // Biru muda untuk background
                        'input-border': '#d1d5db', // Abu-abu muda untuk border input
                        'apple-dark': '#000000',
                    }
                }
            }
        }
    </script>
    <style>
        /* Gaya khusus untuk memastikan elemen sejajar vertikal dan horizontal di tengah */
        .center-content {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="center-content min-h-screen bg-wellmate-light-blue">

    <div class="flex w-full min-h-screen">

        <div class="w-full lg:w-1/3 bg-white p-10 flex flex-col justify-center items-start shadow-xl">
            
            <div class="py-5">
                <div class="-mt-[50px] -mb-[25px] flex items-center no-underline">
                    <img src="assets/logoWellmate.jpg" alt="WellMate Logo" class="h-[140px] -mr-10 -ml-8">
                    <span class="text-[1.4em] text-gray-700 font-bold pb-[15px]">WellMate</span>
                </div>
            </div>

            <div class="flex w-full mb-6 rounded-lg overflow-hidden border border-input-border/50">
                <button class="flex-1 px-6 py-3 text-white bg-wellmate-blue text-sm font-medium">Sign in</button>
                <button onclick="window.location.href='index.php?c=Auth&m=register'" class="flex-1 px-6 py-3 text-gray-600 bg-white hover:bg-gray-50 text-sm font-medium">Sign up</button>
            </div>
            
            <form action="index.php?c=Auth&m=login" method="POST" class="w-full space-y-4">
                <input type="text" name="username" placeholder="Enter your username" class="w-full px-4 py-3 border border-input-border rounded-lg focus:ring-wellmate-blue focus:border-wellmate-blue outline-none text-sm" required>
                <input type="password" name="password" placeholder="Enter your password" class="w-full px-4 py-3 border border-input-border rounded-lg focus:ring-wellmate-blue focus:border-wellmate-blue outline-none text-sm" required>
                
                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center text-gray-600 cursor-pointer">
                        <input type="checkbox" class="form-checkbox text-wellmate-blue w-4 h-4 rounded-full border-gray-300 focus:ring-wellmate-blue" checked>
                        <span class="ml-2">Remember me</span>
                    </label>
                    <a href="#" class="text-wellmate-blue hover:text-wellmate-blue/80 font-medium">Forgot Password?</a>
                </div>

                <button type="submit" onclick="window.location.href='index.php?c=Beranda&m=index'" class="w-full py-3 bg-wellmate-blue text-white font-medium rounded-lg shadow-md hover:bg-wellmate-blue/90 transition duration-150 mt-4">
                    Login
                </button>
            </form>

            <div class="flex items-center w-full my-6">
                <hr class="flex-grow border-t border-input-border">
                <span class="mx-4 text-gray-500 text-sm">or</span>
                <hr class="flex-grow border-t border-input-border">
            </div>

            <div class="w-full flex space-x-4">
                <button class="flex-1 flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                    <img 
                    src="assets/logoApple.png" class="w-6 h-6" alt="WellMate Logo">
                    Sign in with Apple
                </button>

                <button class="flex-1 flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                    <img 
                    src="assets/logoGoogle.png" class="w-6 h-6" alt="WellMate Logo">
                    Sign in with Google
                </button>
            </div>
        </div>

        <div class="hidden lg:block lg:w-2/3 bg-wellmate-light-blue">
            </div>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault(); 
            
            const formData = new FormData(form);
            
            try {
                // Perhatikan: Endpoint API harusnya hanya mengembalikan JSON, 
                // bukan menggunakan 'window.location.href' di tombol.
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });
                
                // Pastikan respons adalah 200 OK dan dapat diparsing
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                
                if (result.success) {
                    // ✅ Lakukan redirect DARI respons JSON
                    // Periksa apakah ada properti redirect (opsional, tergantung implementasi)
                    window.location.href = result.redirect || 'index.php?c=Tracking&m=index'; 
                } else {
                    alert(result.message); // Tampilkan pesan error dari server
                    // Anda mungkin ingin menghapus baris ini untuk implementasi yang lebih baik
                }
            } catch (error) {
                console.error('Error saat sign in:', error);
                // Pesan ini sekarang lebih spesifik untuk kegagalan koneksi/parsing/HTTP
                alert('Terjadi kesalahan koneksi atau respons server tidak valid.');
            }
        });
    });
</script>

</body>
</html>
<?php

require_once 'Controller.class.php';
require_once 'model/FriendModel.class.php';
require_once 'model/NotificationModel.class.php';
require_once 'model/UserModel.class.php';

class Friend extends Controller
{
    private $friendModel;
    private $notificationModel;
    private $usersModel;

    public function __construct()
    {
        $this->friendModel = new FriendModel();
        $this->notificationModel = new NotificationModel();
        $this->usersModel = new UserModel();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $this->listFriends();
    }

    // Menampilkan daftar teman (View: friendList.php)
    public function listFriends()
    {
        $userId = $_SESSION['id_pengguna'] ?? null;
        if (!$userId) {
            header('Location: index.php?c=Users&m=login');
            exit;
        }

        // Ambil daftar teman yang sudah diterima (status: accepted)
        $friends = $this->friendModel->getFriendsByUserId($userId);
        $unreadCount = $this->notificationModel->countUnread($userId);

        //Ambil jumlah permintaan pertemanan dari Model
        $requestCount = $this->friendModel->countFriendRequests($userId);

        // Tampilkan view daftar teman
        $this->view('lihatTeman', [ // Menggunakan nama view: 'lihatTeman'
            'friends' => $friends,
            'user_name' => $_SESSION['nama'] ?? 'Guest',
            'requestCount' => $requestCount,
            'unreadCount' => $unreadCount 
        ]);
    }

    // Menampilkan halaman cari teman
    public function searchFriend()
    {
        $userId = $_SESSION['id_pengguna'] ?? null;
        if (!$userId) {
            header('Location: index.php?c=Users&m=login');
            exit;
        }

        $users = [];
        $query = '';

        // PERUBAHAN: Tangani input dari GET parameter 'q'
        if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
            $query = trim($_GET['q']);
            
            // Cari pengguna berdasarkan username atau nama
            $allUsers = $this->usersModel->searchUsers($query, $userId);
            
            // Tambahkan status pertemanan untuk setiap user
            foreach ($allUsers as &$user) {
                $user['friendship_status'] = $this->friendModel->getFriendshipStatus($userId, $user['id_pengguna']);
            }
            $users = $allUsers;
        }

        //Ambil jumlah permintaan pertemanan dari Model
        $requestCount = $this->friendModel->countFriendRequests($userId);
        $unreadCount = $this->notificationModel->countUnread($userId);

        // Tampilkan view pencarian teman
        $this->view('tambahTeman', [
            'users' => $users,
            'query' => $query,
            'user_name' => $_SESSION['nama'] ?? 'Guest',
            'requestCount' => $requestCount,
            'unreadCount' => $unreadCount 
        ]);
    }

    // Mengirim permintaan pertemanan
    public function sendRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['id_pengguna'] ?? null;
            $friendId = $_POST['id_teman'] ?? null;

            $searchQuery = $_POST['search_query'] ?? '';

            if ($userId && $friendId) {
                // Cek apakah sudah ada hubungan pertemanan
                if ($this->friendModel->isFriendshipActive($userId, $friendId)) {
                    // Pertahankan query pencarian di redirect
                    header("Location: index.php?c=Friend&m=searchFriend&q=" . urlencode($searchQuery) . "&status=exists");
                    exit;
                }

                // Tambahkan permintaan pertemanan
                $success = $this->friendModel->addFriendRequest($userId, $friendId);
                
                if ($success) {
                    // Kirim notifikasi ke pengguna yang ditambahkan
                    $sender = $this->usersModel->getUserById($userId);
                    $senderName = $sender['nama'] ?? 'Seseorang';
                    $message = "Wow! $senderName mengirim permintaan pertemanan.";
                    $this->notificationModel->createNotification($friendId, $message);

                    // Pertahankan query pencarian di redirect
                    header("Location: index.php?c=Friend&m=searchFriend&q=" . urlencode($searchQuery) . "&status=sent");
                    exit;
                }
            }
        }
        $searchQuery = $_POST['search_query'] ?? '';
        header("Location: index.php?c=Friend&m=searchFriend&q=" . urlencode($searchQuery) . "&status=fail");
        exit;
    }

    // Menampilkan daftar permintaan pertemanan yang masuk
    public function listRequests()
    {
        $userId = $_SESSION['id_pengguna'] ?? null;
        if (!$userId) {
            header('Location: index.php?c=Users&m=login');
            exit;
        }

        // Ambil daftar permintaan pertemanan dengan status 'pending'
        $requests = $this->friendModel->getPendingRequests($userId);

        //Ambil jumlah permintaan pertemanan dari Model
        $requestCount = $this->friendModel->countFriendRequests($userId);

        $unreadCount = $this->notificationModel->countUnread($userId);

        // Tampilkan view permintaan pertemanan
        $this->view('permintaanPertemanan', [
            'requests' => $requests,
            'user_name' => $_SESSION['nama'] ?? 'Guest',
            'requestCount' => $requestCount,
            'unreadCount' => $unreadCount 
        ]);
    }

    // Menerima permintaan pertemanan
    public function acceptRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['id_pengguna'] ?? null;
            $friendshipId = $_POST['id_teman'] ?? null;

            if ($userId && $friendshipId) {
                $success = $this->friendModel->updateFriendshipStatus($friendshipId, 'accepted');
                
                if ($success) {
                    // Ambil data pengirim permintaan
                    $friendship = $this->friendModel->getFriendshipById($friendshipId);
                    
                    if ($friendship) {
                        // Ambil nama PENGGUNA yang menerima (yang sedang login) DARI MODEL USER
                        $accepter = $this->usersModel->getUserById($userId);
                        $accepterName = $accepter['nama'] ?? 'Seseorang'; 
                        
                        // Kirim notifikasi ke pengirim
                        $senderId = $friendship['id_pengguna'];
                        $message = "Yay, $accepterName menerima permintaan pertemanan kamu. 🎉"; // Pesan lebih informatif
                        $this->notificationModel->createNotification($senderId, $message);
                    }

                    header("Location: index.php?c=Friend&m=listRequests&status=accepted");
                    exit;
                }
            }
        }
        header("Location: index.php?c=Friend&m=listRequests&status=fail");
        exit;
    }

    // Menolak permintaan pertemanan
    public function rejectRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['id_pengguna'] ?? null;
            $friendshipId = $_POST['id_teman'] ?? null;

            if ($userId && $friendshipId) {
                $success = $this->friendModel->updateFriendshipStatus($friendshipId, 'declined');
                
                if ($success) {
                    // Ambil data pengirim permintaan (opsional: kirim notifikasi penolakan)
                    $friendship = $this->friendModel->getFriendshipById($friendshipId);
                    
                    if ($friendship) {
                        // Ambil nama PENGGUNA yang menolak (yang sedang login) DARI MODEL USER
                        $rejecter = $this->usersModel->getUserById($userId);
                        $rejecterName = $rejecter['nama'] ?? 'Seseorang'; 

                        // Kirim notifikasi ke pengirim
                        $senderId = $friendship['id_pengguna'];
                        $message = "Yah, $rejecterName menolak permintaan pertemanan kamu."; // Pesan lebih spesifik
                        $this->notificationModel->createNotification($senderId, $message);
                    }

                    header("Location: index.php?c=Friend&m=listRequests&status=rejected");
                    exit;
                }
            }
        }
        header("Location: index.php?c=Friend&m=listRequests&status=fail");
        exit;
    }

    // Menghapus teman dari daftar
    public function removeFriend()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['id_pengguna'] ?? null;
            $friendshipId = $_POST['id_teman'] ?? null;

            if ($userId && $friendshipId) {
                $success = $this->friendModel->deleteFriendship($friendshipId, $userId);
                
                if ($success) {
                    header("Location: index.php?c=Friend&m=listFriends&status=removed");
                    exit;
                }
            }
        }
        header("Location: index.php?c=Friend&m=listFriends&status=fail");
        exit;
    }
}
<?php
session_start();
$_SESSION["chat_history"]=[];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Vạn Niên CayTre - Tư vấn phong thủy & cuộc sống</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@400;600&family=Quicksand:wght@400;500;600&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-color: #3a5a40;
            --secondary-color: #588157;
            --accent-color: #a3b18a;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f5f5f5;
            color: var(--dark-text);
            padding-top: 70px;
            padding-bottom: 60px;
        }

        /* Topbar styling */
        .topbar {
            background-color: var(--primary-color);
            box-shadow: var(--shadow);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .topbar .nav-link {
            color: white !important;
            font-weight: 500;
            transition: all 0.3s;
            padding: 1rem 1.5rem;
        }

        .topbar .nav-link:hover {
            background-color: var(--secondary-color);
        }

        /* Calendar styling */
        .calendar-container {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 20px;
            margin-bottom: 20px;
        }

        .calendar-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .calendar-info {
            background-color: #fff9f0;
            border: 1px solid #f0e6d2;
            border-radius: 10px;
            padding: 20px;
            font-size: 0.95rem;
        }

        .calendar-info h5 {
            color: var(--primary-color);
            border-bottom: 1px dashed var(--accent-color);
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        /* Chatbot styling */
        .chatbot-container {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 0;
            height: 600px;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }

        .message {
            margin-bottom: 15px;
            max-width: 80%;
        }

        .user-message {
            margin-left: auto;
            background-color: #e3f2fd;
            border-radius: 15px 15px 0 15px;
        }

        .bot-message {
            margin-right: auto;
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 15px 15px 15px 0;
        }

        .chat-input {
            padding: 15px;
            background-color: white;
            border-top: 1px solid #eee;
            border-radius: 0 0 12px 12px;
        }

        /* Login buttons */
        .login-buttons {
            padding: 30px;
            text-align: center;
        }

        .btn-login {
            width: 100%;
            max-width: 300px;
            margin: 10px auto;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-google {
            background-color: #4285F4;
            color: white;
        }

        .btn-facebook {
            background-color: #3b5998;
            color: white;
        }

        /* Policy and About pages */
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 30px;
            margin-top: 30px;
        }

        /* Bottom bar */
        .bottombar {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 15px;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1000;
        }

        /* Custom fonts */
        .calendar-title {
            font-family: 'Noto Serif JP', serif;
            font-weight: 600;
            color: var(--primary-color);
        }

        .lunar-date {
            font-weight: 600;
            color: #d32f2f;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .topbar .nav-link {
                padding: 0.8rem;
            }

            body {
                padding-top: 60px;
            }
        }

        .bot-message ul {
            padding-left: 20px;
            margin-bottom: 10px;
        }

        .bot-message li {
            margin-bottom: 5px;
        }

        .bot-message strong {
            font-weight: 600;
            color: var(--primary-color);
        }
    </style>
</head>

<body>
    <!-- Topbar -->
    <nav class="navbar navbar-expand-lg topbar">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="#">
                <i class="fas fa-calendar-alt me-2"></i>Lịch Vạn Niên
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon text-white"><i class="fas fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" id="home-link"><i class="fas fa-home me-1"></i> Trang
                            chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="policy-link"><i class="fas fa-file-contract me-1"></i> Chính
                            sách</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="about-link"><i class="fas fa-user me-1"></i> Về tác giả</a>
                    </li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <img src="<?php echo $_SESSION['user']['avatar_url']; ?>" width="30" height="30"
                                    class="rounded-circle me-1">
                                <?php echo htmlspecialchars($_SESSION['user']['name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="auth.php?logout">Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Main Content - Home Page -->
    <div class="container mt-4" id="home-page">
        <div class="row">
            <div class="col-lg-6">
                <!-- Calendar Section -->
                <div class="calendar-container">
                    <div class="calendar-header text-center">
                        <h3 class="mb-3"><i class="fas fa-calendar-day me-2"></i>Lịch Vạn Niên</h3>
                        <div class="d-flex justify-content-center">
                            <input type="date" class="form-control w-75" id="date-picker"
                                value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div class="calendar-info">
                        <h5 class="calendar-title">Thông tin ngày <span
                                id="selected-date"><?php echo date('d/m/Y'); ?></span></h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><i class="fas fa-sun text-warning me-2"></i> <strong>Dương lịch:</strong> <span
                                        id="solar-date"><?php echo date('l, d/m/Y'); ?></span></p>
                                <p><i class="fas fa-moon text-secondary me-2"></i> <strong>Âm lịch:</strong> <span
                                        class="lunar-date" id="lunar-date">Đang tải...</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="fas fa-yin-yang text-primary me-2"></i> <strong>Mệnh ngũ hành:</strong>
                                    <span id="element">Đang tải...</span>
                                </p>
                                <p><i class="fas fa-dragon text-success me-2"></i> <strong>Can Chi:</strong> <span
                                        id="chinese-zodiac">Đang tải...</span></p>
                            </div>
                        </div>

                        <h5 class="calendar-title mt-4">Giờ hoàng đạo</h5>
                        <p id="auspicious-hours">Đang tải...</p>

                        <h5 class="calendar-title mt-4">Ngày tốt/xấu</h5>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2" id="day-quality-badge">TỐT</span>
                            <span id="day-reason"></span>
                        </div>

                        <h5 class="calendar-title mt-4">Việc nên làm</h5>
                        <p id="viecnenlam"></p>

                        <h5 class="calendar-title mt-4">Việc không nên làm</h5>
                        <p id="vieckhongnenlam"></p>

                        <h5 class="calendar-title mt-4">Tuổi xung</h5>
                        <p id="conflicting-ages">Đang tải...</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <!-- Chatbot Section -->
                <div class="chatbot-container">
                    <div class="chat-header">
                        <h4 class="mb-0"><i class="fas fa-robot me-2"></i>CayTre - Trợ lý Phong Thủy</h4>
                    </div>

                    <div class="chat-messages" id="chat-messages">
                        <div class="message p-3 bot-message">
                            <strong>CayTre:</strong> Xin chào! Tôi là CayTre, trợ lý phong thủy và lịch vạn niên. Tôi có
                            thể giúp gì cho bạn hôm nay? 😊
                        </div>
                        <div class="message p-3 bot-message">
                            Bạn có thể hỏi tôi về:
                            <ul class="mt-2">
                                <li>Xem ngày tốt xấu</li>
                                <li>Tư vấn phong thủy nhà ở</li>
                                <li>Chọn hướng tốt</li>
                                <li>Giải đáp về tuổi, mệnh</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Login Section (Shown when not logged in) -->
                    <?php if (!isset($_SESSION['user'])): ?>
                        <div class="login-buttons" id="login-section">
                            <p class="mb-3">Đăng nhập để sử dụng trợ lý AI</p>
                            <a href="auth.php?provider=google" class="btn btn-google btn-login mb-2">
                                <i class="fab fa-google me-2"></i> Đăng nhập với Google
                            </a>
                            <a href="auth.php?provider=facebook" class="btn btn-facebook btn-login">
                                <i class="fab fa-facebook me-2"></i> Đăng nhập với Facebook
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Chat Input (Shown when logged in) -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="chat-input" id="chat-input-section">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Nhập câu hỏi của bạn..."
                                    id="user-input">
                                <button class="btn btn-primary" type="button" id="send-btn">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                            <small class="text-muted mt-2 d-block">CayTre sử dụng Z.AI để xử lý câu hỏi của
                                bạn</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4 d-none" id="policy-page">
        <div class="content-card">
            <h2 class="mb-4 text-center"><i class="fas fa-file-contract me-2"></i>Chính Sách</h2>

            <div class="policy-content">
                <p class="lead text-center">
                    "Website được xây dựng phục vụ mục đích cá nhân và phi lợi nhuận. Dữ liệu được cung cấp từ các nguồn
                    công khai. Người dùng chịu trách nhiệm với nội dung họ nhập vào hệ thống."
                </p>

                <div class="mt-5">
                    <h4><i class="fas fa-shield-alt me-2 text-primary"></i>Bảo Mật Thông Tin</h4>
                    <p>Chúng tôi cam kết bảo vệ thông tin cá nhân của người dùng. Mọi dữ liệu cá nhân được cung cấp sẽ
                        chỉ được sử dụng cho mục đích cải thiện trải nghiệm người dùng và không được chia sẻ với bên thứ
                        ba.</p>

                    <h4 class="mt-4"><i class="fas fa-database me-2 text-primary"></i>Thu Thập Dữ Liệu</h4>
                    <p>Website có thể thu thập thông tin không nhận dạng cá nhân như loại trình duyệt, thiết bị truy
                        cập, thời gian truy cập để phân tích và cải thiện dịch vụ.</p>

                    <h4 class="mt-4"><i class="fas fa-exclamation-triangle me-2 text-primary"></i>Giới Hạn Trách Nhiệm
                    </h4>
                    <p>Thông tin trên website chỉ mang tính chất tham khảo. Chúng tôi không chịu trách nhiệm cho bất kỳ
                        quyết định nào dựa trên thông tin được cung cấp bởi hệ thống.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4 d-none" id="about-page">
        <div class="content-card">
            <h2 class="mb-4 text-center"><i class="fas fa-user me-2"></i>Về Tác Giả</h2>

            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200' viewBox='0 0 200 200'%3E%3Ccircle cx='100' cy='100' r='90' fill='%233a5a40'/%3E%3Ccircle cx='100' cy='85' r='35' fill='%23f9f9f9'/%3E%3Cpath d='M100,120 Q125,150 75,150 Z' fill='%23f9f9f9'/%3E%3C/svg%3E"
                        class="img-fluid rounded-circle mb-3" alt="Tác giả" style="max-width: 200px;">
                </div>
                <div class="col-md-8">
                    <h3>{{ Tên tác giả }}</h3>
                    <p class="text-muted"><i class="fas fa-code me-2"></i>{{ ok }}</p>

                    <div class="mt-4">
                        <h4><i class="fas fa-book-open me-2"></i>Giới Thiệu</h4>
                        <p>{{ Mô tả bản thân, kinh nghiệm và đam mê với văn hóa truyền thống, phong thủy và công nghệ }}
                        </p>

                        <h4 class="mt-4"><i class="fas fa-graduation-cap me-2"></i>Học Vấn & Kinh Nghiệm</h4>
                        <ul>
                            <li>{{ Trình độ học vấn và chuyên môn }}</li>
                            <li>{{ Kinh nghiệm làm việc trong lĩnh vực công nghệ }}</li>
                            <li>{{ Nghiên cứu về văn hóa phương Đông và phong thủy }}</li>
                        </ul>

                        <h4 class="mt-4"><i class="fas fa-envelope me-2"></i>Liên Hệ</h4>
                        <p>
                            <i class="fas fa-envelope me-2"></i> {{ Email liên hệ }}<br>
                            <i class="fab fa-facebook me-2"></i> {{ Facebook cá nhân }}<br>
                            <i class="fab fa-github me-2"></i> {{ Tài khoản GitHub }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Navigation handling
        document.getElementById('home-link').addEventListener('click', function (e) {
            e.preventDefault();
            showPage('home-page');
            setActiveLink(this);
        });

        document.getElementById('policy-link').addEventListener('click', function (e) {
            e.preventDefault();
            showPage('policy-page');
            setActiveLink(this);
        });

        document.getElementById('about-link').addEventListener('click', function (e) {
            e.preventDefault();
            showPage('about-page');
            setActiveLink(this);
        });

        function showPage(pageId) {
            // Hide all pages
            document.getElementById('home-page').classList.add('d-none');
            document.getElementById('policy-page').classList.add('d-none');
            document.getElementById('about-page').classList.add('d-none');

            // Show selected page
            document.getElementById(pageId).classList.remove('d-none');
        }

        function setActiveLink(link) {
            // Remove active class from all links
            document.querySelectorAll('.nav-link').forEach(el => {
                el.classList.remove('active');
            });

            // Add active class to clicked link
            link.classList.add('active');
        }

        // Date picker functionality
        const datePicker = document.getElementById('date-picker');
        const selectedDate = document.getElementById('selected-date');

        datePicker.addEventListener('change', function () {
            const date = new Date(this.value);
            const formattedDate = formatDate(date);
            selectedDate.textContent = formattedDate;
            fetchCalendarData(this.value);
        });

        function formatDate(date) {
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        async function fetchWithRetry(url, options, retries = 3) {
            try {
                const response = await fetch(url, options);

                // Check for HTTP error status codes (e.g., 4xx, 5xx)
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP error! Status: ${response.status}, Message: ${errorText}`);
                }

                return response;
            } catch (error) {
                console.error(`Fetch failed: ${error.message}`);
                if (retries > 0) {
                    console.log(`Retrying... (${retries} attempts left)`);
                    await new Promise(resolve => setTimeout(resolve, 1000)); // 1-second delay
                    return fetchWithRetry(url, options, retries - 1);
                } else {
                    console.error("Max retries reached. Fetch failed permanently.");
                    throw error;
                }
            }
        }
        // Fetch calendar data from API
        function fetchCalendarData(date) {
            fetch(`api/calendar.php?date=${date}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    updateCalendarUI(data);
                })
                .catch(error => {
                    console.error('Error fetching calendar data:', error);
                    alert('Có lỗi xảy ra khi tải dữ liệu lịch: ' + error.message);
                });
        }

        function updateCalendarUI(data) {
            document.getElementById('solar-date').textContent = data.solar_date;
            document.getElementById('lunar-date').textContent = data.lunar_date;
            document.getElementById('element').textContent = data.element;
            document.getElementById('chinese-zodiac').textContent = data.chinese_zodiac || 'N/A';
            document.getElementById('auspicious-hours').textContent = data.auspicious_hours;
            document.getElementById('day-reason').textContent = data.day_reason;
            //recommended_activities
            document.getElementById('viecnenlam').textContent = data.recommended_activities;
            document.getElementById('vieckhongnenlam').textContent = data.avoid_activities;

            // Update day quality badge
            const dayQualityBadge = document.getElementById('day-quality-badge');
            if (data.day_quality.toLowerCase().includes('tốt')) {
                dayQualityBadge.className = 'badge bg-success me-2';
            } else if (data.day_quality.toLowerCase().includes('xấu')) {
                dayQualityBadge.className = 'badge bg-danger me-2';
            } else {
                dayQualityBadge.className = 'badge bg-warning me-2';
            }
            dayQualityBadge.textContent = data.day_quality.split(' ')[0].toUpperCase();

            //document.getElementById('day-quality').textContent = data.day_quality;
            document.getElementById('conflicting-ages').textContent = data.conflicting_ages || 'N/A';
        }

        // Load initial calendar data
        fetchCalendarData(datePicker.value);

        // Chatbot functionality
        const sendBtn = document.getElementById('send-btn');
        const userInput = document.getElementById('user-input');
        const chatMessages = document.getElementById('chat-messages');

        if (sendBtn) {
            // Send message functionality
            sendBtn.addEventListener('click', sendMessage);
            userInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        }

        function sendMessage() {
            const message = userInput.value.trim();
            if (message === '') return;

            // Add user message
            addMessage(message, 'user');
            userInput.value = '';

            // Send to API
            fetch('api/chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: message })
            })
                .then(response => {
                    if (response.status === 401) {
                        throw new Error('Unauthorized');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        addMessage("Có lỗi xảy ra: " + data.error, 'bot');
                    } else {
                        addMessage(data.reply, 'bot');
                    }
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                })
                .catch(error => {
                    console.error('Error:', error);
                    addMessage("Xin lỗi, tôi gặp sự cố khi trả lời. Vui lòng thử lại.", 'bot');
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                });
        }
        function formatBotMessage(text) {
            // Thay thế **text** thành <strong>text</strong>
            let formatted = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

            // Thay thế * ở đầu dòng thành danh sách
            formatted = formatted.replace(/\n\*\s+(.*?)(?=\n|$)/g, '\n<li>$1</li>');

            // Thêm thẻ <ul> bao quanh các mục danh sách
            formatted = formatted.replace(/(<li>.*<\/li>)/gs, '<ul>$1</ul>');

            // Thay thế xuống dòng thành thẻ <br>
            formatted = formatted.replace(/\n/g, '<br>');

            return formatted;
        }
        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', 'p-3');

            if (sender === 'user') {
                messageDiv.classList.add('user-message');
                messageDiv.innerHTML = `${text}`;
            } else {
                messageDiv.classList.add('bot-message');
                messageDiv.innerHTML = `<strong>CayTre:</strong> ${formatBotMessage(text)}`;
            }

            chatMessages.appendChild(messageDiv);

            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    </script>
</body>

</html>
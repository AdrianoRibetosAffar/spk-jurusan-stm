<?php
session_start();

// Ambil data dari session
$jurusanTerbaik = $_SESSION['jurusanTerbaik'] ?? 'Belum Ada';
$skorAkhir = $_SESSION['skorAkhir'] ?? [];
$persentase = $_SESSION['persentase'] ?? [];
$jawabanSiswa = $_SESSION['jawabanSiswa'] ?? [];

// Labels untuk kriteria
$labels = [
    'q1' => 'Mampu memecahkan masalah',
    'q2' => 'Ketelitian',
    'q3' => 'Kemampuan berkomunikasi',
    'q4' => 'Bekerja sama',
    'q5' => 'Programming',
    'q6' => 'Menggambar',
    'q7' => 'Keterampilan Pengoperasian Mesin',
    'q8' => 'Pelajaran Matematika',
    'q9' => 'Pelajaran Fisika',
    'q10' => 'Pelajaran Kimia'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Rekomendasi Jurusan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(255, 255, 255, 0.1) 10px,
                rgba(255, 255, 255, 0.1) 20px
            );
            animation: slide 20s linear infinite;
        }
        
        @keyframes slide {
            0% { transform: translateX(-50px); }
            100% { transform: translateX(50px); }
        }
        
        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .content {
            padding: 40px;
        }
        
        .section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .section:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .section-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title .icon {
            font-size: 1.6rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .answers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
        }
        
        .answer-item {
            background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #4facfe;
            transition: all 0.3s ease;
        }
        
        .answer-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.2);
        }
        
        .answer-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
        }
        
        .answer-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #4facfe;
            margin-top: 5px;
        }
        
        .ranking-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .ranking-item::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .ranking-item:hover::before {
            left: 100%;
        }
        
        .rank-1 {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #8b4513;
            border: 2px solid #ffd700;
        }
        
        .rank-2 {
            background: linear-gradient(135deg, #c0c0c0 0%, #e5e5e5 100%);
            color: #555;
            border: 2px solid #c0c0c0;
        }
        
        .rank-3 {
            background: linear-gradient(135deg, #cd7f32 0%, #daa520 100%);
            color: #fff;
            border: 2px solid #cd7f32;
        }
        
        .rank-other {
            background: linear-gradient(135deg, #f1f3f4 0%, #e8eaf6 100%);
            color: #5f6368;
            border: 2px solid #e0e0e0;
        }
        
        .rank-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }
        
        .rank-badge {
            font-size: 2rem;
            font-weight: bold;
            min-width: 60px;
            text-align: center;
        }
        
        .rank-details h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .rank-score {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .rank-percentage {
            text-align: right;
            font-weight: 700;
        }
        
        .percentage-bar {
            width: 120px;
            height: 8px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }
        
        .percentage-fill {
            height: 100%;
            background: linear-gradient(90deg, #4facfe, #00f2fe);
            border-radius: 4px;
            transition: width 1s ease-in-out;
            animation: fillBar 2s ease-in-out;
        }
        
        @keyframes fillBar {
            from { width: 0%; }
        }
        
        .recommendation {
            background: linear-gradient(135deg, #4caf50 0%, #8bc34a 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .recommendation::before {
            content: "✨";
            position: absolute;
            font-size: 8rem;
            opacity: 0.1;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: sparkle 3s ease-in-out infinite;
        }
        
        @keyframes sparkle {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.1; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.2; }
        }
        
        .recommendation h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .recommended-major {
            font-size: 2.5rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from { text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); }
            to { text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3), 0 0 20px rgba(255, 255, 255, 0.5); }
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .btn-back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .text-center {
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .content {
                padding: 20px;
            }
            
            .answers-grid {
                grid-template-columns: 1fr;
            }
            
            .ranking-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .rank-percentage {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container fade-in">
        <div class="header">
            <h1>🎓 Sistem Rekomendasi Jurusan</h1>
            <p>Hasil Analisis Menggunakan Metode Simple Additive Weighting (SAW)</p>
        </div>

        <div class="content">
            <!-- Profil Kemampuan Siswa -->
            <div class="section fade-in">
                <div class="section-title">
                    <span class="icon">📊</span>Profil Kemampuan Siswa
                </div>
                <div class="answers-grid">
                    <?php foreach ($jawabanSiswa as $key => $value): ?>
                        <div class="answer-item">
                            <div class="answer-label"><?= htmlspecialchars($labels[$key]) ?></div>
                            <div class="answer-value"><?= $value ?>/5 ⭐</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Ranking Jurusan -->
            <div class="section fade-in">
                <div class="section-title">
                    <span class="icon">🏆</span>Ranking Jurusan Berdasarkan Kesesuaian
                </div>
                <?php 
                $rank = 1;
                foreach ($skorAkhir as $nama => $skor): 
                    $rankClass = $rank === 1 ? 'rank-1' : ($rank === 2 ? 'rank-2' : ($rank === 3 ? 'rank-3' : 'rank-other'));
                    $badge = $rank === 1 ? "🥇" : ($rank === 2 ? "🥈" : ($rank === 3 ? "🥉" : "#$rank"));
                ?>
                    <div class="ranking-item <?= $rankClass ?>">
                        <div class="rank-info">
                            <div class="rank-badge"><?= $badge ?></div>
                            <div class="rank-details">
                                <h3><?= htmlspecialchars($nama) ?></h3>
                                <div class="rank-score">Skor: <?= $skor ?></div>
                            </div>
                        </div>
                        <div class="rank-percentage">
                            <div style="font-size: 1.4rem; font-weight: 700;"><?= $persentase[$nama] ?>%</div>
                            <div class="percentage-bar">
                                <div class="percentage-fill" style="width: <?= $persentase[$nama] ?>%;"></div>
                            </div>
                        </div>
                    </div>
                <?php 
                    $rank++;
                endforeach; 
                ?>
            </div>

            <!-- Rekomendasi -->
            <div class="section recommendation fade-in">
                <h3>🎯 Rekomendasi Jurusan Terbaik</h3>
                <div class="recommended-major"><?= htmlspecialchars($jurusanTerbaik) ?></div>
                <p style="margin-top: 15px; font-size: 1.1rem; opacity: 0.9;">
                    Berdasarkan analisis kemampuan dan minat Anda, jurusan ini memiliki tingkat kesesuaian tertinggi dengan profil Anda.
                </p>
            </div>

            <!-- Tombol Kembali -->
            <div class="text-center">
                <a href="../index.html" class="btn-back">
                    🔙 Kembali ke Formulir
                </a>
            </div>
        </div>
    </div>

    <script>
        // Animate percentage bars
        window.addEventListener("load", function() {
            const bars = document.querySelectorAll(".percentage-fill");
            bars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = "0%";
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });
        });
        
        // Add stagger animation to sections
        const sections = document.querySelectorAll(".section");
        sections.forEach((section, index) => {
            section.style.animationDelay = (index * 0.2) + "s";
        });
    </script>
</body>
</html>
document.addEventListener("DOMContentLoaded", function () {
    const questions = document.querySelectorAll(".question");
    const submitButtonContainer = document.getElementById("submit-button-container");
    const totalQuestions = questions.length; // Menggunakan jumlah pertanyaan yang ada di HTML
    let answeredQuestions = 0;
    
    console.log(`Total pertanyaan terdeteksi: ${totalQuestions}`);
    
    // Fungsi untuk memeriksa apakah semua pertanyaan telah dijawab
    function checkAllQuestionsAnswered() {
        if (answeredQuestions >= totalQuestions) {
            console.log("Semua pertanyaan telah dijawab, menampilkan tombol kirim");
            submitButtonContainer.style.display = "block";
            setTimeout(() => {
                submitButtonContainer.style.opacity = "1";
                
                // Scroll ke tombol submit
                setTimeout(() => {
                    submitButtonContainer.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 100);
            }, 50);
        } else {
            console.log(`${answeredQuestions} dari ${totalQuestions} pertanyaan telah dijawab`);
        }
    }
    
    // Fungsi untuk menangani jawaban
    function handleAnswer(question) {
        // Periksa apakah pertanyaan sudah dijawab sebelumnya
        if (question.classList.contains("answered")) {
            console.log("Pertanyaan ini sudah dijawab sebelumnya");
            return; // Jangan lakukan apa-apa jika sudah dijawab
        }
        
        // Ambil data-id dari pertanyaan
        const questionId = parseInt(question.getAttribute("data-id"));
        console.log(`Pertanyaan ${questionId} dijawab`);
        
        // Tambahkan kelas answered untuk efek buram
        question.classList.add("answered");
        console.log(`Kelas 'answered' ditambahkan ke pertanyaan ${questionId}`);
        
        // Nonaktifkan input di pertanyaan ini
        const inputs = question.querySelectorAll("input[type='radio']");
        inputs.forEach(input => {
            input.disabled = true;
        });
        
        // Increment jumlah pertanyaan yang dijawab
        answeredQuestions++;
        
        // Periksa apakah semua pertanyaan telah dijawab
        checkAllQuestionsAnswered();
        
        // Tampilkan pertanyaan berikutnya
        const nextQuestionId = questionId + 1;
        const nextQuestion = document.querySelector(`.question[data-id="${nextQuestionId}"]`);
        
        if (nextQuestion) {
            console.log(`Menampilkan pertanyaan ${nextQuestionId}`);
            nextQuestion.style.display = "block";
            
            // Gunakan setTimeout untuk memastikan transisi opacity berfungsi
            setTimeout(() => {
                nextQuestion.style.opacity = "1";
                
                // Scroll ke pertanyaan berikutnya dengan animasi halus
                setTimeout(() => {
                    nextQuestion.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 100); // Sedikit delay untuk memastikan pertanyaan sudah terlihat
                
            }, 50);
        } else {
            console.log(`Tidak ada pertanyaan berikutnya setelah ${questionId}`);
            
            // Jika ini adalah pertanyaan terakhir dan semua pertanyaan telah dijawab,
            // tampilkan tombol submit
            if (answeredQuestions >= totalQuestions) {
                checkAllQuestionsAnswered();
            }
        }
    }
    
    // Tambahkan event listener ke semua radio button
    questions.forEach(question => {
        const inputs = question.querySelectorAll("input[type='radio']");
        
        inputs.forEach(input => {
            input.addEventListener("change", function() {
                handleAnswer(question);
            });
        });
    });
    
    // Periksa apakah ada pertanyaan yang sudah dijawab saat halaman dimuat
    // (misalnya jika pengguna me-refresh halaman)
    questions.forEach(question => {
        const inputs = question.querySelectorAll("input[type='radio']:checked");
        if (inputs.length > 0) {
            handleAnswer(question);
        }
    });
});


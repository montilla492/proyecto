
// Acordeón para las FAQs
document.addEventListener("DOMContentLoaded", () => {
    const faqItems = document.querySelectorAll('.fc-faq-item');

    faqItems.forEach(item => {
        const btn = item.querySelector('.fc-faq-question');
        btn.addEventListener('click', () => {
            const isOpen = item.classList.contains('is-open');
            faqItems.forEach(i => i.classList.remove('is-open'));
            if (!isOpen) {
                item.classList.add('is-open');
            }
        });
    });
});


// Fondo animado de túnel para login/registro
const canvasReg = document.getElementById('canvas-registro');
if (canvasReg) {
    const ctx = canvasReg.getContext('2d');
    let stars = [];
    const numStars = 200;
    const speed = 0.05;

    function init() {
        canvasReg.width = window.innerWidth;
        canvasReg.height = window.innerHeight;
        stars = [];
        for (let i = 0; i < numStars; i++) {
            stars.push({
                x: (Math.random() - 0.5) * canvasReg.width,
                y: (Math.random() - 0.5) * canvasReg.height,
                z: Math.random() * canvasReg.width,
                color: i % 2 === 0 ? "#7b5cf7" : "#ff2a6d"
            });
        }
    }

    function animate() {
        ctx.fillStyle = "rgba(8, 8, 16, 0.2)";
        ctx.fillRect(0, 0, canvasReg.width, canvasReg.height);

        const cx = canvasReg.width / 2;
        const cy = canvasReg.height / 2;

        stars.forEach(s => {
            let x = s.x / (s.z / canvasReg.width);
            let y = s.y / (s.z / canvasReg.width);
            let size = (1 - s.z / canvasReg.width) * 3;

            if (x + cx > 0 && x + cx < canvasReg.width && y + cy > 0 && y + cy < canvasReg.height) {
                ctx.beginPath();
                ctx.strokeStyle = s.color;
                ctx.lineWidth = size;
                ctx.lineCap = "round";

                let prevZ = s.z + 15;
                let px = s.x / (prevZ / canvasReg.width);
                let py = s.y / (prevZ / canvasReg.width);

                ctx.moveTo(x + cx, y + cy);
                ctx.lineTo(px + cx, py + cy);
                ctx.stroke();
            }

            s.z -= speed * 150;

            if (s.z <= 0) {
                s.z = canvasReg.width;
                s.x = (Math.random() - 0.5) * canvasReg.width;
                s.y = (Math.random() - 0.5) * canvasReg.height;
            }
        });

        requestAnimationFrame(animate);
    }

    window.addEventListener('resize', init);
    init();
    animate();
}

// Animación de aparición al hacer scroll
document.addEventListener("DOMContentLoaded", () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, { threshold: 0.15 });

    const targets = document.querySelectorAll('.fc-section-title, .fc-feature-card, .fc-obra-card, .fc-blog-item, .fc-stat-card, .fc-qs-hero');
    targets.forEach(target => {
        target.classList.add('reveal');
        observer.observe(target);
    });
});


// Activa una pestaña del dashboard por su ID
function activateTab(tabId) {
    const triggerEl = document.querySelector(`#${tabId}`);
    if (triggerEl) {
        bootstrap.Tab.getOrCreateInstance(triggerEl).show();
        window.scrollTo({
            top: triggerEl.getBoundingClientRect().top + window.scrollY - 120,
            behavior: 'smooth'
        });
    }
}

// Muestra u oculta campos según el tipo de obra seleccionado
function toggleUploadInputs() {
    const select = document.getElementById('obraTypeSelect');
    const mediaContainer = document.getElementById('mediaUploadContainer');
    const textContainer = document.getElementById('textRelatoContainer');

    if (select && mediaContainer && textContainer) {
        if (select.value === 'relato') {
            mediaContainer.classList.add('d-none');
            textContainer.classList.remove('d-none');
        } else {
            mediaContainer.classList.remove('d-none');
            textContainer.classList.add('d-none');
        }
    }
}




//para los Likes

document.getElementById('likeBtn').addEventListener('click', function() {
        const btn = this;
        const obraId = btn.getAttribute('data-id');
        
        // Evitar doble clic
        btn.disabled = true;
        
        fetch('like_obra.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + obraId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('likesCount').textContent = data.likes;
                btn.innerHTML = '<i class="bi bi-heart-fill me-1"></i> ¡Liked!';
                btn.classList.remove('btn-outline-danger');
                btn.classList.add('btn-danger');
            } else {
                alert(data.error || 'Ocurrió un error');
                btn.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
        });
    });
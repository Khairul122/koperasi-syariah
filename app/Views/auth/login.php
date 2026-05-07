<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synectra - Secure Access</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS & Alpine.js for Shadcn-like Interaction -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Three.js for 3D Elements -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <!-- SynAlert Component -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/components/alert-dialog.css">
    <script src="<?= BASE_URL ?>/assets/js/components/alert-dialog.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        neo: {
                            yellow: '#FFD600',
                            lime: '#A3E635',
                            pink: '#FF4081',
                            bg: '#F0F0F0',
                        }
                    },
                    boxShadow: {
                        'neo': '4px 4px 0px 0px #000000',
                        'neo-lg': '8px 8px 0px 0px #000000',
                        'neo-hover': '2px 2px 0px 0px #000000',
                    }
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @layer components {
            .shadcn-input {
                @apply w-full bg-white border-[3px] border-black px-4 py-3 font-semibold text-black placeholder:text-gray-500 focus:outline-none focus:shadow-neo transition-all;
            }
            .shadcn-button {
                @apply w-full bg-neo-yellow border-[3px] border-black px-4 py-4 font-extrabold text-black uppercase tracking-wider shadow-neo active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all;
            }
            .shadcn-card {
                @apply bg-white border-[4px] border-black p-8 shadow-neo-lg relative z-10;
            }
        }
        canvas {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>
<body x-data="loginHandler" class="bg-neo-bg font-['Plus_Jakarta_Sans'] min-h-screen flex items-center justify-center p-4 overflow-hidden">
    
    <!-- 3D Background Container -->
    <canvas id="canvas3d"></canvas>


    <div class="w-full max-w-md shadcn-card">
        <!-- Logo/Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-neo-lime border-[3px] border-black shadow-neo mb-6 rotate-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
            </div>
            <h1 class="text-4xl font-[900] tracking-tighter mb-2 italic">SYNECTRA</h1>
            <div class="inline-block bg-black text-white px-3 py-1 text-xs font-bold uppercase tracking-widest">
                Protected Access
            </div>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="submitLogin" class="space-y-6">
            <div>
                <label class="block text-xs font-extrabold uppercase mb-2 tracking-widest">Identity / Email</label>
                <input type="email" x-model="form.email" class="shadcn-input" placeholder="USER@SYNECTRA.IO" required>
            </div>

            <div>
                <label class="block text-xs font-extrabold uppercase mb-2 tracking-widest">Access Key</label>
                <input type="password" x-model="form.password" class="shadcn-input" placeholder="••••••••" required>
            </div>

            <div class="flex items-center justify-between py-2">
                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox" class="hidden peer">
                    <div class="w-6 h-6 border-[3px] border-black bg-white peer-checked:bg-neo-lime shadow-neo-hover mr-3 transition-all"></div>
                    <span class="text-sm font-bold uppercase">Stay logged</span>
                </label>
                <a href="#" class="text-sm font-bold uppercase underline decoration-2 underline-offset-4 hover:bg-neo-yellow">Recovery</a>
            </div>

            <button type="submit" :disabled="loading" class="shadcn-button">
                <template x-if="!loading">
                    <span class="flex items-center justify-center">
                        Initiate Connection <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </span>
                </template>
                <template x-if="loading">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-black mx-auto"></div>
                </template>
            </button>

            <div class="text-center pt-4">
                <p class="text-xs font-bold uppercase text-gray-600">
                    System Node: <span class="text-black">404-SYNE-2026</span>
                </p>
            </div>
        </form>
    </div>

    <!-- 3D Background Script (Hardware Lab Theme) -->
    <script>
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ canvas: document.getElementById('canvas3d'), alpha: true, antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);

        const group = new THREE.Group();
        scene.add(group);

        // Helper to create Neo-Hardware
        function createHardware(type) {
            const container = new THREE.Group();
            const blackMat = new THREE.MeshBasicMaterial({ color: 0x000000 });
            const wireMat = new THREE.MeshBasicMaterial({ color: 0x000000, wireframe: true });
            
            if (type === 'cpu') {
                // Main Chip Body
                const body = new THREE.Mesh(new THREE.BoxGeometry(1.5, 0.2, 1.5), wireMat);
                container.add(body);
                // Pins
                for(let i=0; i<4; i++) {
                    const pin = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.05, 1.4), blackMat);
                    pin.position.x = (i - 1.5) * 0.4;
                    container.add(pin);
                }
            } else if (type === 'server') {
                // Tall Server Rack
                const body = new THREE.Mesh(new THREE.BoxGeometry(0.8, 2.5, 0.8), wireMat);
                container.add(body);
                // Front Panels
                for(let i=0; i<5; i++) {
                    const panel = new THREE.Mesh(new THREE.BoxGeometry(0.7, 0.1, 0.05), blackMat);
                    panel.position.y = (i - 2) * 0.4;
                    panel.position.z = 0.4;
                    container.add(panel);
                }
            } else {
                // Small IoT Component
                container.add(new THREE.Mesh(new THREE.IcosahedronGeometry(0.5, 0), wireMat));
            }

            return container;
        }

        const items = [];
        const types = ['cpu', 'server', 'iot'];

        for (let i = 0; i < 12; i++) {
            const hardware = createHardware(types[i % 3]);
            hardware.position.set(
                (Math.random() - 0.5) * 20,
                (Math.random() - 0.5) * 20,
                (Math.random() - 0.5) * 10
            );
            hardware.rotation.set(Math.random()*Math.PI, Math.random()*Math.PI, 0);
            hardware.userData = {
                rotSpeed: Math.random() * 0.01,
                floatSpeed: Math.random() * 0.005,
                offset: Math.random() * 1000
            };
            group.add(hardware);
            items.push(hardware);
        }

        // Add "Data Stream" particles
        const pointsGeom = new THREE.BufferGeometry();
        const pointsCount = 200;
        const coords = new Float32Array(pointsCount * 3);
        for(let i=0; i<pointsCount*3; i++) coords[i] = (Math.random()-0.5)*30;
        pointsGeom.setAttribute('position', new THREE.BufferAttribute(coords, 3));
        const pointsMat = new THREE.PointsMaterial({ color: 0x000000, size: 0.05 });
        const particles = new THREE.Points(pointsGeom, pointsMat);
        scene.add(particles);

        camera.position.z = 10;

        let mouseX = 0, mouseY = 0;
        document.addEventListener('mousemove', (e) => {
            mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
            mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
        });

        function animate() {
            requestAnimationFrame(animate);

            items.forEach((item) => {
                item.rotation.x += item.userData.rotSpeed;
                item.rotation.y += item.userData.rotSpeed;
                item.position.y += Math.sin(Date.now() * 0.001 + item.userData.offset) * item.userData.floatSpeed;
            });

            particles.rotation.y += 0.001;

            camera.position.x += (mouseX * 4 - camera.position.x) * 0.05;
            camera.position.y += (-mouseY * 4 - camera.position.y) * 0.05;
            camera.lookAt(scene.position);

            renderer.render(scene, camera);
        }

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        animate();
    </script>

    <!-- Logic Script (Alpine.js Handler) -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loginHandler', () => ({
                form: {
                    email: '',
                    password: ''
                },
                loading: false,

                async submitLogin() {
                    this.loading = true;
                    SynAlert.loading('Authenticating...', 'Verifying your credentials, please wait.');

                    try {
                        const response = await fetch('<?= BASE_URL ?>/login', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(this.form)
                        });

                        const result = await response.json();

                        if (result.success) {
                            SynAlert.success('Access Granted', result.message || 'Redirecting to dashboard...');
                            setTimeout(() => window.location.href = result.redirect, 1400);
                        } else {
                            SynAlert.error('Auth Failed', result.message || 'Invalid credentials. Please try again.');
                            this.loading = false;
                        }
                    } catch (error) {
                        SynAlert.error('System Error', 'Communication interrupted. Check your connection.');
                        this.loading = false;
                    }
                },
            }));
        });
    </script>
</body>
</html>

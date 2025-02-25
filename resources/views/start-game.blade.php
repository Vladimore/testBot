<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Vertical Jump Game</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }
        canvas {
            background: #87CEEB; /* Sky blue background */
            display: block;
            margin: 0 auto;
            border: 2px solid #000;
        }
        #menu {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            display: none;
            color: #fff;
        }
        #menu h1 {
            font-size: 48px;
            margin: 0;
        }
        #startButton, #restartButton {
            padding: 20px;
            font-size: 24px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<canvas id="gameCanvas" width="400" height="600"></canvas>
<div id="menu">
    <h1>Vertical Jump Game</h1>
    <button id="startButton">Start Game</button>
</div>
<script>
    const canvas = document.getElementById('gameCanvas');
    const ctx = canvas.getContext('2d');

    // Game settings
    const gravity = 0.5;
    const jumpStrength = 10;
    const moveSpeed = 5; // Movement speed to the left/right
    const platformCount = 5;
    const platforms = [];
    let player;
    let keys = {};
    let gameStarted = false;

    // Player object
    function resetPlayer() {
        player = {
            x: 0,
            y: 0,
            width: 30,
            height: 30,
            speedY: 0,
            onGround: false,

            draw: function() {
                ctx.fillStyle = 'red';
                ctx.fillRect(this.x, this.y, this.width, this.height);
            },

            update: function() {
                if (!this.onGround) {
                    this.speedY += gravity; // Apply gravity
                } else {
                    this.speedY = 0; // Reset speed if on ground
                }
                this.y += this.speedY;

                // Check for ground collision
                this.onGround = false;
                for (let platform of platforms) {
                    if (this.y + this.height >= platform.y && this.y + this.height <= platform.y + platform.height && this.x + this.width > platform.x && this.x < platform.x + platform.width) {
                        this.y = platform.y - this.height; // Place player on top of platform
                        this.onGround = true;
                    }
                }

                // Prevent player from going off the bottom of the screen
                if (this.y > canvas.height) {
                    this.y = canvas.height;
                    this.onGround = true;
                    gameOver();
                }

                // Handle horizontal movement
                if (keys['KeyA']) { // Move left on "A"
                    this.x -= moveSpeed;
                }
                if (keys['KeyD']) { // Move right on "D"
                    this.x += moveSpeed;
                }

                // Prevent player from going off the edges of the canvas
                if (this.x < 0) { this.x = 0; }
                if (this.x + this.width > canvas.width) { this.x = canvas.width - this.width; }
            }
        };

        // Position player on the first platform
        setStartingPosition();
    }

    // Set player starting position on a randomly selected platform
    function setStartingPosition() {
        const platform = platforms[Math.floor(Math.random() * platforms.length)];
        player.x = platform.x + (platform.width / 2) - (player.width / 2);
        player.y = platform.y - player.height; // Position above the platform
    }

    // Reset platforms
    function resetPlatforms() {
        platforms.length = 0; // Clear existing platforms
        for (let i = 0; i < platformCount; i++) {
            createPlatform();
        }
    }

    // Platform constructor
    function createPlatform() {
        const x = Math.random() * (canvas.width - 80);
        const y = Math.random() * (canvas.height - 20); // Generate a random Y position
        const width = 80;
        const height = 20;
        platforms.push({ x, y, width, height });
    }

    // Game loop
    function gameLoop() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        player.update();
        player.draw();

        // Draw platforms
        ctx.fillStyle = 'green';
        for (let platform of platforms) {
            ctx.fillRect(platform.x, platform.y, platform.width, platform.height);
        }

        requestAnimationFrame(gameLoop);
    }

    // Jump function
    function jump() {
        if (player.onGround) {
            player.speedY = -jumpStrength;
            player.onGround = false;
        }
    }

    // Event listeners for keyboard input
    window.addEventListener('keydown', (e) => {
        keys[e.code] = true;
        if (keys['KeyW']) { // Jump on "W"
            jump();
        }
    });

    window.addEventListener('keyup', (e) => {
        keys[e.code] = false;
    });

    // Start the game
    function startGame() {
        resetPlatforms(); // Reset platforms first
        resetPlayer(); // Then reset player
        gameStarted = true;
        document.getElementById('menu').style.display = 'none';
        gameLoop();
    }

    // Show game over menu
    function gameOver() {
        gameStarted = false;
        document.getElementById('menu').style.display = 'block';
        document.getElementById('menu').innerHTML = `
                <h1>Game Over</h1>
                <button id="restartButton">Restart Game</button>
            `;
        document.getElementById('restartButton').onclick = startGame; // Button event for restarting
    }

    // Event listener for the start button
    document.getElementById('startButton').onclick = startGame;

    // Show menu initially
    document.getElementById('menu').style.display = 'block';
</script>
</body>
</html>

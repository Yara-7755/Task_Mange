<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <div style="max-width: 550px; margin: 40px auto; background: white; border-radius: 240px; padding: 55px 45px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <h2 style="font-size: 26px; margin-bottom: 25px; color: #114525;">⏱️ Focus Timer</h2>

            <select id="taskSelect" style="width: 100%; padding: 12px; border-radius: 10px; border: 2px solid #e5e7eb; margin-bottom: 35px; font-size: 16px;">
                <option value="">-- Select a task --</option>
                @foreach ($tasks as $task)
                    <option value="{{ $task->id }}">{{ $task->name }}</option>
                @endforeach
            </select>

            <div style="position: relative; width: 320px; height: 320px; margin: 0 auto 35px;">
                <svg width="320" height="320" style="transform: rotate(-90deg);">
                    <circle
                        cx="160" cy="160" r="140"
                        fill="none"
                        stroke="#e5e7eb"
                        stroke-width="18"
                    />
                    <circle
                        id="progressCircle"
                        cx="160" cy="160" r="140"
                        fill="none"
                        stroke="#9ca3af"
                        stroke-width="18"
                        stroke-linecap="round"
                        stroke-dasharray="879.6"
                        stroke-dashoffset="0"
                        style="transition: stroke-dashoffset 1s linear, stroke 0.4s ease;"
                    />
                </svg>

                <div id="timerDisplay" style="
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            font-size: 56px;
            font-weight: bold;
            color: #164e2e;
            font-family: monospace;
        ">
                    25:00
                </div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: center;">
                <button id="startBtn" style="padding: 16px 34px; border: none; border-radius: 12px; background: #164e2e; color: white; font-size: 17px; font-weight: bold; cursor: pointer;">Start</button>
                <button id="pauseBtn" style="padding: 16px 34px; border: none; border-radius: 12px; background: #f59e0b; color: white; font-size: 17px; font-weight: bold; cursor: pointer;">Pause</button>
                <button id="resetBtn" style="padding: 16px 34px; border: none; border-radius: 12px; background: #dc2626; color: white; font-size: 17px; font-weight: bold; cursor: pointer;">Reset</button>
            </div>

        </div>

        <script>
            let totalSeconds = 25 * 60;
            let elapsedSeconds = 0;
            let timerInterval = null;
            let isRunning = false;

            const display = document.getElementById('timerDisplay');
            const progressCircle = document.getElementById('progressCircle');
            const startBtn = document.getElementById('startBtn');
            const pauseBtn = document.getElementById('pauseBtn');
            const resetBtn = document.getElementById('resetBtn');
            const taskSelect = document.getElementById('taskSelect');

            const CIRCLE_LENGTH = 879.6;
            function setCircleColor(color) {
                progressCircle.style.stroke = color;
            }
            function updateDisplay() {
                let remaining = totalSeconds - elapsedSeconds;
                let minutes = Math.floor(remaining / 60);
                let seconds = remaining % 60;
                display.textContent =
                    String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

                let progress = elapsedSeconds / totalSeconds;
                let offset = CIRCLE_LENGTH * progress;
                progressCircle.style.strokeDashoffset = offset;
            }

            startBtn.addEventListener('click', function () {
                if (isRunning) return;
                if (!taskSelect.value) {
                    alert('Please select a task first');
                    return;
                }
                isRunning = true;
                setCircleColor('#164e2e');

                timerInterval = setInterval(function () {
                    elapsedSeconds++;
                    updateDisplay();

                    if (elapsedSeconds >= totalSeconds) {
                        clearInterval(timerInterval);
                        isRunning = false;
                        saveTime();
                        alert('Time is up! Great work 🎉');
                    }
                }, 1000);
            });

            pauseBtn.addEventListener('click', function () {
                clearInterval(timerInterval);
                isRunning = false;
                setCircleColor('#f59e0b');
                saveTime();
            });
            resetBtn.addEventListener('click', function () {
                clearInterval(timerInterval);
                isRunning = false;
                elapsedSeconds = 0;
                setCircleColor('#9ca3af');
                updateDisplay();
            });

            function saveTime() {
                if (elapsedSeconds === 0 || !taskSelect.value) return;

                fetch(`/tasks/${taskSelect.value}/add-time`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ seconds: elapsedSeconds }),
                });

                elapsedSeconds = 0;
            }
        </script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

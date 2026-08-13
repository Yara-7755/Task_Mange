@extends('layouts.app')

@section('title', 'Focus Timer - Task Manager')


@section('styles')

    /* =====================================================
    FOCUS TIMER
    Liquid Countdown Style
    Navy + Beige
    ===================================================== */

    .timer-page {
    max-width: 900px;
    margin: 35px auto 0;
    padding: 0 20px 30px;
    }


    /* =====================================================
    TIMER PANEL
    ===================================================== */

    .timer-panel {
    position: relative;

    min-height: 650px;

    display: flex;
    flex-direction: column;
    align-items: center;

    padding: 45px 30px 50px;

    background: var(--color-navy);

    border-radius: 30px;

    color: #fff;

    overflow: hidden;

    box-shadow:
    0 22px 45px rgba(36, 59, 83, 0.22);
    }


    /* subtle background circles */

    .timer-panel::before {
    content: "";

    position: absolute;

    width: 380px;
    height: 380px;

    top: -220px;
    right: -170px;

    border: 1px solid rgba(243, 238, 220, 0.08);

    border-radius: 50%;
    }

    .timer-panel::after {
    content: "";

    position: absolute;

    width: 300px;
    height: 300px;

    bottom: -210px;
    left: -150px;

    border: 1px solid rgba(243, 238, 220, 0.07);

    border-radius: 50%;
    }


    /* =====================================================
    SELECT
    ===================================================== */

    .timer-panel select {
    position: relative;
    z-index: 5;

    width: min(430px, 90%);

    height: 48px;

    margin: 0 auto 38px;

    padding: 0 18px;

    background: rgba(255, 255, 255, 0.96);

    border: none;

    border-radius: 10px;

    color: var(--color-primary-dark);

    font-size: 14px;

    outline: none;

    cursor: pointer;

    box-shadow:
    0 8px 20px rgba(0,0,0,.10);
    }

    .timer-panel select:focus {
    box-shadow:
    0 0 0 3px rgba(243, 238, 220, .30);
    }


    /* =====================================================
    TIMER CIRCLE
    ===================================================== */

    .timer-circle {
    position: relative;
    z-index: 3;

    width: 330px;
    height: 330px;

    margin: 10px auto 42px;

    border: 3px solid rgba(243, 238, 220, 0.85);

    border-radius: 50%;

    overflow: hidden;

    background: rgba(255,255,255,.025);

    box-shadow:
    inset 0 0 35px rgba(0,0,0,.08),
    0 0 0 1px rgba(255,255,255,.04);

    display: flex;
    align-items: center;
    justify-content: center;
    }


    /* =====================================================
    LIQUID
    ===================================================== */

    .timer-circle .timer-liquid {
    position: absolute;

    left: -5%;

    bottom: 0;

    width: 110%;

    height: 0%;

    background: var(--color-border);

    z-index: 1;

    transition: height 1s linear;

    border-radius: 50% 50% 0 0 / 12% 12% 0 0;

    box-shadow:
    0 -4px 14px rgba(243, 238, 220, .08);
    }


    /*
    Wave on top of liquid
    */

    .timer-circle .timer-liquid::before {
    content: "";

    position: absolute;

    left: -10%;

    top: -12px;

    width: 120%;

    height: 25px;

    background: var(--color-border);

    border-radius: 50%;

    transform: rotate(-2deg);
    }


    /*
    Second wave layer
    */

    .timer-circle .timer-liquid::after {
    content: "";

    position: absolute;

    left: -10%;

    top: -7px;

    width: 120%;

    height: 18px;

    background: rgba(255,255,255,.08);

    border-radius: 50%;

    transform: rotate(2deg);
    }


    /* =====================================================
    HIDE ORIGINAL SVG VISUAL
    ===================================================== */

    .timer-circle svg {
    position: absolute;

    width: 100%;
    height: 100%;

    opacity: 0;

    pointer-events: none;
    }


    /* =====================================================
    TIMER NUMBER
    ===================================================== */

    #timerDisplay {
    position: relative;

    z-index: 4;

    top: auto;
    left: auto;

    transform: none;

    display: flex;
    flex-direction: column;
    align-items: center;

    font-family: var(--font-mono);

    font-size: 56px;

    font-weight: 700;

    letter-spacing: 2px;

    color: #fff;

    text-shadow:
    0 3px 12px rgba(0,0,0,.15);
    }


    /* label under timer */

    #timerDisplay::after {
    content: "FOCUS TIME";

    display: block;

    margin-top: 6px;

    font-family: Arial, sans-serif;

    font-size: 10px;

    font-weight: 600;

    letter-spacing: 4px;

    color: rgba(243, 238, 220, .85);
    }


    /* =====================================================
    BUTTONS
    ===================================================== */

    .timer-buttons {
    position: relative;
    z-index: 5;

    display: flex;

    align-items: center;
    justify-content: center;

    gap: 35px;

    width: 100%;
    }


    /*
    Make buttons circular,
    inspired by the reference design
    */

    .timer-buttons button {
    width: 64px;
    height: 64px;

    min-width: 64px;

    padding: 0;

    border: none;

    border-radius: 50%;

    color: var(--color-navy);

    font-size: 0;

    font-weight: 700;

    cursor: pointer;

    transition:
    transform .2s ease,
    box-shadow .2s ease;
    }


    /* START */

    #startBtn {
    background: var(--color-border);

    box-shadow:
    0 8px 18px rgba(0,0,0,.18);
    }

    #startBtn::before {
    content: "▶";

    font-size: 19px;
    }


    /* PAUSE */

    #pauseBtn {
    background: var(--color-warning);

    color: #fff;

    box-shadow:
    0 8px 18px rgba(0,0,0,.18);
    }

    #pauseBtn::before {
    content: "Ⅱ";

    font-size: 20px;
    }


    /* RESET */

    #resetBtn {
    background: transparent;

    border: 2px solid rgba(243,238,220,.75);

    color: var(--color-border);

    box-shadow: none;
    }

    #resetBtn::before {
    content: "↻";

    font-size: 27px;
    }


    /* Hover */

    .timer-buttons button:hover {
    transform: translateY(-4px) scale(1.04);
    }

    #startBtn:hover,
    #pauseBtn:hover {
    box-shadow:
    0 13px 25px rgba(0,0,0,.25);
    }


    /* =====================================================
    BACK DASHBOARD
    ===================================================== */

    .back-dashboard {
    display: block;

    width: fit-content;

    margin: 25px auto 0;

    padding: 9px 16px;

    border-radius: 20px;

    color: var(--color-primary-dark);

    text-decoration: none;

    font-size: 14px;

    font-weight: 600;

    transition: .2s ease;
    }

    .back-dashboard:hover {
    background: rgba(31,92,55,.07);

    color: var(--color-navy);

    text-decoration: none;
    }


    /* =====================================================
    MOBILE
    ===================================================== */

    @media (max-width: 700px) {

    .timer-page {
    width: 94%;

    padding: 0 8px 20px;
    }

    .timer-panel {
    min-height: 580px;

    padding: 35px 18px 40px;

    border-radius: 24px;
    }

    .timer-circle {
    width: 270px;
    height: 270px;
    }

    #timerDisplay {
    font-size: 44px;
    }

    .timer-buttons {
    gap: 20px;
    }

    .timer-buttons button {
    width: 58px;
    height: 58px;
    min-width: 58px;
    }
    }
@endsection


@section('content')

    <!-- ==========================================
     Hero
     ========================================== -->

    <div class="hero-panel">

        <div class="page-header">

            <div class="icon-box">
                ⏱️
            </div>

        </div>

        <h1>
            Focus Timer
        </h1>

        <p class="subtitle">
            Choose a task and focus for 25 minutes.
        </p>

    </div>


    <!-- ==========================================
         Timer
         ========================================== -->

    <div class="task-section timer-page">

        <div class="timer-panel">


            <!-- Task Selection -->

            <select id="taskSelect">

                <option value="">
                    -- Select a task --
                </option>

                @foreach ($pendingTasks as $task)

                    <option value="{{ $task->id }}">
                        {{ $task->name }}
                    </option>

                @endforeach

            </select>


            <!-- Timer Circle -->
            <div class="timer-circle">

                <!-- Liquid Fill -->
                <div class="timer-liquid" id="timerLiquid"></div>

                <!-- Keep SVG because JavaScript uses progressCircle -->
                <svg
                    width="260"
                    height="260"
                >

                    <circle
                        cx="130"
                        cy="130"
                        r="112"
                        fill="none"
                        stroke="transparent"
                        stroke-width="16"
                    />

                    <circle
                        id="progressCircle"
                        cx="130"
                        cy="130"
                        r="112"
                        fill="none"
                        stroke="transparent"
                        stroke-width="16"
                        stroke-linecap="round"
                        stroke-dasharray="703.7"
                        stroke-dashoffset="0"
                    />

                </svg>


                <div id="timerDisplay">
                    25:00
                </div>

            </div>

            <!-- Timer Buttons -->

            <div class="timer-buttons">

                <button
                    id="startBtn"
                    type="button"
                >
                    Start
                </button>


                <button
                    id="pauseBtn"
                    type="button"
                >
                    Pause
                </button>


                <button
                    id="resetBtn"
                    type="button"
                >
                    Reset
                </button>

            </div>

        </div>

    </div>


    <!-- ==========================================
         Back to Dashboard
         ========================================== -->

    <a
        href="{{ route('dashboard') }}"
        class="back-dashboard"
    >
        ← Back to Dashboard
    </a>


    <script>

        /*
  |--------------------------------------------------------------------------
  | Timer Variables
  |--------------------------------------------------------------------------
  */

        let totalSeconds = 25 * 60;

        let elapsedSeconds = 0;

        let savedSeconds = 0;

        let timerInterval = null;

        let isRunning = false;


        /*
        |--------------------------------------------------------------------------
        | Elements
        |--------------------------------------------------------------------------
        */

        const display =
            document.getElementById('timerDisplay');

        const timerLiquid =
            document.getElementById('timerLiquid');

        const progressCircle =
            document.getElementById('progressCircle');

        const startBtn =
            document.getElementById('startBtn');

        const pauseBtn =
            document.getElementById('pauseBtn');

        const resetBtn =
            document.getElementById('resetBtn');

        const taskSelect =
            document.getElementById('taskSelect');




        const CIRCLE_LENGTH = 703.7;




        function setCircleColor(color) {

            progressCircle.style.stroke = color;

        }




        function updateDisplay() {

            const remaining =
                totalSeconds - elapsedSeconds;


            const minutes =
                Math.floor(remaining / 60);


            const seconds =
                remaining % 60;


            display.textContent =
                String(minutes).padStart(2, '0') +
                ':' +
                String(seconds).padStart(2, '0');


            const progress =
                elapsedSeconds / totalSeconds;

            const offset =
                CIRCLE_LENGTH * progress;


            progressCircle.style.strokeDashoffset =
                offset;




            const remainingPercent =
                ((totalSeconds - elapsedSeconds) / totalSeconds) * 100;


            timerLiquid.style.height =
                remainingPercent + '%';

        }




        startBtn.addEventListener('click', function () {

            if (isRunning) {
                return;
            }


            if (!taskSelect.value) {

                alert('Please select a task first');

                return;
            }




            isRunning = true;


            setCircleColor('#1f5c37');


            timerInterval = setInterval(function () {

                elapsedSeconds++;

                updateDisplay();




                if (elapsedSeconds >= totalSeconds) {

                    clearInterval(timerInterval);

                    timerInterval = null;

                    isRunning = false;


                    saveTime();


                    alert(
                        'Time is up! Great work 🎉'
                    );

                }

            }, 1000);

        });




        pauseBtn.addEventListener('click', function () {

            if (!isRunning) {
                return;
            }


            clearInterval(timerInterval);

            timerInterval = null;

            isRunning = false;


            setCircleColor('#c08a2e');




            saveTime();

        });




        resetBtn.addEventListener('click', function () {

            clearInterval(timerInterval);

            timerInterval = null;

            isRunning = false;

            elapsedSeconds = 0;

            savedSeconds = 0;


            setCircleColor('#93a099');


            updateDisplay();

        });




        function saveTime() {

            if (
                elapsedSeconds === 0 ||
                !taskSelect.value
            ) {
                return;
            }




            const newSeconds =
                elapsedSeconds - savedSeconds;


            if (newSeconds <= 0) {
                return;
            }


            fetch(
                `/tasks/${taskSelect.value}/add-time`,
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN':
                            '{{ csrf_token() }}',
                    },

                    body: JSON.stringify({
                        seconds: newSeconds
                    }),
                }
            );



            savedSeconds = elapsedSeconds;

        }




        updateDisplay();

    </script>

@endsection

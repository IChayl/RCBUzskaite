<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>RCB Inventāra uzskaite</title>
 
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>?v=<?php echo e(filemtime(public_path('favicon.ico'))); ?>" sizes="any">
   
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>?v=<?php echo e(filemtime(public_path('favicon.ico'))); ?>">
   
    <meta name="theme-color" content="#490700">
    <!-- Inline data-URI favicon (fallback) -->
    
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">

    <!-- Icons & Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Navy-Maroon theme + bloom buttons + decorative shapes -->
    <style>
        :root{
            --navy: #490700;
            --navy-2: #75150b;
            --maroon: #5400A8;
            --maroon-2: #7528c3;
            --accent: #f3c6c9;
        }

        html,body{
            height:100%;
            margin:0;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: radial-gradient(1200px 800px at 10% 20%, rgba(131, 43, 155, 0.12), transparent 8%),
                        radial-gradient(1000px 600px at 90% 80%, rgba(9, 51, 7, 0.12), transparent 10%),
                        linear-gradient(180deg, var(--navy) 0%, var(--maroon) 100%);
            color: #150024;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
        }

        /* Decorative blurred shapes behind content */
        .page-shapes{
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: visible;
        }
        .page-shapes .blob{
            position: absolute;
            filter: blur(36px) saturate(120%);
            opacity: 0.9;
            transform: translate3d(0,0,0);
            mix-blend-mode: screen;
            border-radius: 50%;
        }
        .page-shapes .b1{
            width: 420px;
            height: 420px;
            left: -10%;
            top: -6%;
            /* background: radial-gradient(circle at 30% 30%, rgba(142, 43, 155, 0.95), rgba(131, 43, 155, 0.6) 40%, transparent 70%); */
        }
        .page-shapes .b2{
            width: 520px;
            height: 520px;
            right: -8%;
            bottom: -14%;
            /* background: radial-gradient(circle at 70% 70%, rgba(186, 7, 22, 0.95), rgba(217, 100, 106, 0.55) 40%, transparent 72%); */
        }
        .page-shapes .b3{
            width: 260px;
            height: 260px;
            right: 10%;
            top: 12%;
            background: radial-gradient(circle at 30% 70%, rgba(133, 43, 155, 0.65), rgba(133, 43, 155, 0.25));
            opacity: 0.6;
            filter: blur(56px);
        }

        /* Layout layers */
        header, main, footer{
            position: relative;
            z-index: 2; /* above shapes */
        }

        .container{
            /* padding-top: 3.5rem;
            padding-bottom: 3.5rem; */
        }



        header h1{
            margin:0;
            font-size:1.75rem;
            letter-spacing:0.6px;
            color: #FFFFFF;
            text-shadow: 
            0 0 10px rgba(227, 0, 0, 0.91),
            0 0 20px rgba(155, 43, 58, 0.95),
            0 0 30px rgba(186, 36, 255, 0.95),
            0 6px 20px rgba(247, 0, 255, 0.93);
        }

        /* Card style for main content to create shape */
        .card-surface{
            //background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 18px;
            padding: 1.5rem;
            box-shadow: 0 8px 30px rgba(2, 23, 8, 0.6), inset 0 1px 0 rgba(255,255,255,0.02);
            backdrop-filter: blur(6px) saturate(120%);
        }

        /* Bloom button style */
        .bloom-button{
            display:inline-block;
            background: linear-gradient(90deg, var(--maroon) 0%, var(--maroon-2) 50%, var(--navy-2) 100%);
            color: #FFFFFF  ;
            border: none;
            padding: .6rem 1rem;
            border-radius: 999px;
            font-weight:600;
            letter-spacing: .4px;
            box-shadow:
                0 6px 18px rgba(155,43,58,0.28),
                0 0 12px rgba(155,43,58,0.14),
                inset 0 1px 0 rgba(255,255,255,0.04);
            transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
            cursor: pointer;
            text-decoration: none;
            -webkit-tap-highlight-color: transparent;
        }
        .bloom-button:focus{
            outline: 3px solid rgba(255,198,200,0.14);
            outline-offset: 4px;
        }
        .bloom-button:hover{
            transform: translateY(-4px) scale(1.02);
            box-shadow:
                0 16px 40px rgba(155,43,58,0.32),
                0 0 40px rgba(155,43,58,0.28),
                inset 0 1px 0 rgba(255,255,255,0.05);
            filter: saturate(120%) brightness(1.06);
        }
        .bloom-button:active{
            transform: translateY(-1px) scale(0.995);
            box-shadow:
                0 8px 22px rgba(155,43,58,0.22),
                0 0 18px rgba(155,43,58,0.16);
        }

        /* Small utility */
        .spaced{
            gap: .75rem;
            display:inline-flex;
            align-items:center;
        }

        /* Responsive tweaks */
        @media (max-width: 768px){
            header h1{ font-size:1.25rem; }
            .card-surface{ padding:1rem; border-radius:12px; }
        }
    </style>
</head>
<body>
    <div class="page-shapes" aria-hidden="true">
        <div class="blob b1"></div>
        <div class="blob b2"></div>
        <div class="blob b3"></div>
    </div>



<!-- Update the header element to include the new class -->
<header class="small">
    <h1>RCB inventāra uzskaite</h1>
        <div>
            
            <?php echo $__env->make('inc.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>


    </header>

    <main style="padding-bottom: 100px" class="container">
        <section class="card-surface">
            <?php echo $__env->yieldContent('content'); ?>
        </section>
    </main>

  

    <footer class="container" style="margin-top:1.25rem;">
        <?php echo $__env->make('inc.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </footer>
</body>
</html>

<?php /**PATH C:\Users\anita\Herd\try2\resources\views/layout/app.blade.php ENDPATH**/ ?>
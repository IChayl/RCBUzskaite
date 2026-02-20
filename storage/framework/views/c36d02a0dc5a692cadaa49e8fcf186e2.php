

<?php $__env->startSection('content'); ?>
    <p style="color: #ffffff;">Visi inventāri</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/inventars/create">Jauns inventārs</a>
    </div>

    <hr>
    <h2 style="color: #ffffff;">Inventāri</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
    <?php $__empty_1 = true; $__currentLoopData = $inventari; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="background: #490700; color: white; width: 340px;" class="card mt-3">
            <div class="card-body">
                <p class="card-text">Nosaukums: <?php echo e($item->nosaukums); ?></p>
                <p class="card-text">Apraksts: <?php echo e($item->apraksts ?? '-'); ?></p>
                <p class="card-text">Nolietojums: <?php echo e($item->nolietojums ?? '-'); ?></p>
                <p class="card-text">Statuss: <?php echo e($item->statuss ?? '-'); ?></p>
                <p class="card-text">Kategorija ID: <?php echo e($item->kategorija_id); ?></p>
                <p class="card-text">Atrašanās vieta ID: <?php echo e($item->atrasanas_vieta_id); ?></p>
                <div  class="auth-links">
                    <a href="#" class="delete-btn" data-id="<?php echo e($item->inventars_id); ?>">Dzēst</a>
                    <a href="/inventars/<?php echo e($item->inventars_id); ?>/details">Detalizēta</a>
                    <a href="/inventars/<?php echo e($item->inventars_id); ?>/edit">Rediģēt</a>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p>Nav ierakstu.</p>
    <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.querySelector('div[style*="background: #490700"]');
        if (alert) {
            alert.style.cursor = 'pointer';
            alert.addEventListener('click', function() {
                this.remove();
            });
        }

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                if (confirm('Vai vēlaties dzēst šo ierakstu ?')) {
                    window.location.href = `/inventars/${id}/delete`;
                }
            });
        });
    });
</script>
<div style="color: #ffffff; margin-top: 20px;">
    <?php if(session('success')): ?>
        <div style="background: #490700; color: #90EE90; padding: 12px 16px; border-radius: 4px; border-left: 4px solid #90EE90;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
</div>
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\anita\Herd\try2\resources\views/inventars.blade.php ENDPATH**/ ?>
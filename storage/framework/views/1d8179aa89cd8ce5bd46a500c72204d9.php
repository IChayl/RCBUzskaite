

<?php $__env->startSection('content'); ?>
    <p style="color: #ffffff;">Visas inventāra kustības</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/inventara_kustiba/create">Jauna kustība</a>
    </div>

    <hr>
    <h2 style="color: #ffffff;">Inventāra kustība</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
    <?php $__empty_1 = true; $__currentLoopData = $kustibas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="background: #490700; color: white; width: 340px;" class="card mt-3">
            <div class="card-body">
                <p class="card-text">Datums: <?php echo e($item->datums); ?></p>
                <p class="card-text">Inventārs ID: <?php echo e($item->inventars_id); ?></p>
                <p class="card-text">No vietas: <?php echo e($item->no_atrasanas_vietas_id); ?></p>
                <p class="card-text">Uz vietu: <?php echo e($item->uz_atrasanas_vietas_id); ?></p>
                <p class="card-text">Atbildīgais: <?php echo e($item->atbildigais_lietotajs_id); ?></p>
                <div  class="auth-links">
                    <a href="#" class="delete-btn" data-id="<?php echo e($item->kustiba_id); ?>">Dzēst</a>
                    <a href="/inventara_kustiba/<?php echo e($item->kustiba_id); ?>/details">Detalizēta</a>
                    <a href="/inventara_kustiba/<?php echo e($item->kustiba_id); ?>/edit">Rediģēt</a>
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
                    window.location.href = `/inventara_kustiba/${id}/delete`;
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
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\anita\Herd\try2\resources\views/inventara_kustiba.blade.php ENDPATH**/ ?>
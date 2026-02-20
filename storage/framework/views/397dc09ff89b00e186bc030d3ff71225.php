

<?php $__env->startSection('content'); ?>
    <p style="color: #ffffff;">Visi lietotāji</p>

    <div class="auth-links">
        <a href="/">Atpakaļ uz sākumlapu</a>
        <a href="/lietotajs/create">Jauns lietotājs</a>
    </div>

    <hr>
    <h2 style="color: #ffffff;">Lietotāji</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
    <?php $__empty_1 = true; $__currentLoopData = $lietotaji; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="background: #490700; color: white; width: 340px;" class="card mt-3">
            <div class="card-body">
                <p class="card-text">Vārds: <?php echo e($item->lietotajvards); ?></p>
                <p class="card-text">Admina tiesības: <?php echo e($item->admina_tiesibas? 'Jā':'Nē'); ?></p>
                <div  class="auth-links">
                    <a href="#" class="delete-btn" data-id="<?php echo e($item->lietotajs_id); ?>">Dzēst</a>
                    <a href="/lietotajs/<?php echo e($item->lietotajs_id); ?>/details">Detalizēta</a>
                    <a href="/lietotajs/<?php echo e($item->lietotajs_id); ?>/edit">Rediģēt</a>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p>Nav lietotāju.</p>
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
                    window.location.href = `/lietotajs/${id}/delete`;
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
<?php echo $__env->make('layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\anita\Herd\try2\resources\views/lietotaji.blade.php ENDPATH**/ ?>
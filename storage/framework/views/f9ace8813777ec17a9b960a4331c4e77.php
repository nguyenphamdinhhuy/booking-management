

<?php $__env->startSection('content'); ?>
    <style>
        /* Font chung cho toàn bộ nội dung */
        body,
        input,
        textarea,
        select,
        button,
        a {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.4;
        }

        /* Tiêu đề */
        h1,
        h3 {
            font-weight: 700;
            color: #222;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        h3 {
            font-size: 1.25rem;
            margin-bottom: 10px;
        }

        .album-row {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .album-card {
            flex: 1 1 30%;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: #fff;
            border: 1px solid #e0e0e0;
        }

        .album-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .album-card img {
            min-height: 200px;
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #e0e0e0;
            transition: transform 0.3s ease;
        }

        .album-card:hover img {
            transform: scale(1.05);
        }

        .album-card .card-body {
            padding: 1rem 1.25rem;
            background-color: #fafafa;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .album-card .card-body input,
        .album-card .card-body textarea,
        .album-card .card-body select {
            width: 100%;
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: 1px solid #ccc;
            font-size: 0.95rem;
            font-family: inherit;
            color: #333;
        }

        /* Nút Thêm banner */
        .btn-banner {
            display: inline-block;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            border-radius: 0.75rem;
            padding: 0.6rem 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            text-align: center;
            text-decoration: none;
            margin-bottom: 1rem;
        }

        .btn-banner:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-banner:active {
            transform: translateY(1px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        /* Nút Cập nhật Album */
        button.btn-primary {
            background-color: #2575fc;
            border: none;
            border-radius: 0.5rem;
            padding: 0.6rem 1.2rem;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 1rem;
        }

        button.btn-primary:hover {
            background-color: #1a5edb;
        }
    </style>

    <h1>Quản lý Album Banner</h1>

    <a href="<?php echo e(route('admin.banner.create')); ?>" class="btn-banner">Thêm banner</a>

    <?php $__currentLoopData = $albums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $albumCode => $album): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($albumCode): ?>
                        <h3>Album: <?php echo e($albumCode); ?></h3>

            <form action="<?php echo e(route('admin.banner.updateAlbum', $albumCode)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="album-row">
                    <?php $__currentLoopData = $album; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="album-card">
                            <img src="<?php echo e(asset('storage/' . $banner->images_path)); ?>" alt="Banner">
                            <div class="card-body">
                                <input type="hidden" name="b_id[]" value="<?php echo e($banner->b_id); ?>">
                                <input type="text" name="title[]" value="<?php echo e($banner->title); ?>" placeholder="Tiêu đề">
                                <textarea name="description[]" placeholder="Mô tả"><?php echo e($banner->description); ?></textarea>
                                <input type="file" name="image[]">
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <label for="status">Trạng thái Album:</label>
                <select name="status" id="status" class="form-control mt-3">
                    
                    <option value="1" <?php echo e($album[0]->status == 1 ? 'selected' : ''); ?>>Hiển thị</option>
                    <option value="0" <?php echo e($album[0]->status == 0 ? 'selected' : ''); ?>>Ẩn</option>
                </select>

                <button type="submit" class="btn btn-primary mt-3">Cập nhật Album</button>
            </form>

                        <hr>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/banner/index.blade.php ENDPATH**/ ?>
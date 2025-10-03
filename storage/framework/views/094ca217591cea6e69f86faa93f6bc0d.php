

<?php $__env->startSection('content'); ?>
    <h1>Thêm Album Banner (3 ảnh)</h1>

    <form action="<?php echo e(route('admin.banner.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="banner-row">
            <?php for($i = 0; $i < 3; $i++): ?>
                <div class="banner-card">
                    <div class="mb-3">
                        <label>Tiêu đề Banner <?php echo e($i + 1); ?></label>
                        <input type="text" name="title[]" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Mô tả Banner <?php echo e($i + 1); ?></label>
                        <textarea name="description[]" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Ảnh Banner <?php echo e($i + 1); ?></label>
                        <input type="file" name="images_path[]" class="form-control banner-input" accept="image/*" required>
                        <div class="preview-container">
                            <img id="preview-<?php echo e($i); ?>" src="" alt="Preview Banner <?php echo e($i + 1); ?>">
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control">
                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Thêm Album</button>
    </form>

   <style>
    /* Reset */
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f2f5;
        color: #333;
    }

    form {
        max-width: 1100px;
        margin: 2rem auto;
        padding: 2rem;
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        margin-bottom: 2rem;
        font-size: 1.8rem;
        color: #2c3e50;
    }

    .banner-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        justify-content: space-between;
    }

    .banner-card {
        flex: 1 1 30%;
        background: #fdfdfd;
        padding: 1.2rem;
        border-radius: 1rem;
        border: 1px solid #e2e2e2;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease;
    }

    .banner-card:hover {
        transform: translateY(-3px);
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    label {
        font-weight: 600;
        display: block;
        margin-bottom: 0.5rem;
        color: #444;
    }

    input.form-control,
    textarea.form-control,
    select.form-control {
        width: 100%;
        padding: 0.7rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #ccc;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    input.form-control:focus,
    textarea.form-control:focus,
    select.form-control:focus {
        border-color: #2575fc;
        box-shadow: 0 0 0 3px rgba(37, 117, 252, 0.15);
        outline: none;
    }

    .preview-container img {
        width: 100%;
        margin-top: 0.6rem;
        border-radius: 0.5rem;
        object-fit: cover;
        max-height: 160px;
        border: 1px solid #ddd;
        display: none;
        transition: transform 0.3s ease;
    }

    .preview-container img:hover {
        transform: scale(1.03);
    }

    button.btn-primary {
        display: block;
        margin: 2rem auto 0;
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        border: none;
        border-radius: 0.75rem;
        padding: 0.8rem 2.2rem;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        font-size: 1rem;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        transition: all 0.2s;
    }

    button.btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.25);
    }

    button.btn-primary:active {
        transform: translateY(1px);
        box-shadow: 0 3px 14px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 992px) {
        .banner-card {
            flex: 1 1 48%;
        }
    }

    @media (max-width: 600px) {
        .banner-card {
            flex: 1 1 100%;
        }
    }
</style>


    <script>
        // JS hiển thị preview ảnh
        document.querySelectorAll('.banner-input').forEach((input, index) => {
            input.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.getElementById('preview-' + index);
                        img.src = e.target.result;
                        img.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/banner/create.blade.php ENDPATH**/ ?>


<?php $__env->startSection('content'); ?>
                        <style>
                            .staff-grid {
                                display: flex;
                                flex-wrap: wrap;
                                gap: 32px;
                                margin-top: 32px;
                                justify-content: center;
                            }
                            #staffModalContent {
        transform: scale(0.95);
        transition: all 0.3s ease;
    }
    #staffModal.show #staffModalContent {
        transform: scale(1);
    }


                            .staff-card {
                                background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
                                border-radius: 18px;
                                box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08), 0 1.5px 4px rgba(0, 0, 0, 0.04);
                                padding: 28px 22px;
                                width: 360px;
                                display: flex;
                                gap: 18px;
                                align-items: flex-start;
                                transition: transform 0.18s, box-shadow 0.18s;
                                border: 1px solid #e5e7eb;
                                position: relative;
                            }

                            .staff-card:hover {
                                transform: translateY(-6px) scale(1.02);
                                box-shadow: 0 12px 32px rgba(0, 0, 0, 0.13), 0 2px 8px rgba(0, 0, 0, 0.07);
                                border-color: #b6d0f7;
                            }

                            .staff-card img {
                                width: 96px;
                                height: 128px;
                                object-fit: cover;
                                border-radius: 10px;
                                border: 2px solid #d1d5db;
                                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
                                transition: border-color 0.2s;
                                background: #f1f5f9;
                            }

                            .staff-card:hover img {
                                border-color: #60a5fa;
                            }

                            .staff-info {
                                flex: 1;
                                min-width: 0;
                            }

                            .staff-info h4 {
                                margin: 0 0 8px;
                                font-size: 1.18rem;
                                font-weight: 600;
                                color: #1e293b;
                                letter-spacing: 0.01em;
                            }

                            .staff-info p {
                                margin: 0 0 6px;
                                font-size: 15px;
                                color: #475569;
                                line-height: 1.5;
                                word-break: break-word;
                            }

                            .btn-detail {
                                display: inline-block;
                                margin-top: 10px;
                                padding: 7px 18px;
                                background: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%);
                                color: #fff;
                                border-radius: 7px;
                                text-decoration: none;
                                font-weight: 500;
                                font-size: 15px;
                                box-shadow: 0 2px 8px rgba(96, 165, 250, 0.13);
                                transition: background 0.18s, box-shadow 0.18s;
                                border: none;
                            }

                            .btn-detail:hover {
                                background: linear-gradient(90deg, #1d4ed8 0%, #38bdf8 100%);
                                box-shadow: 0 4px 16px rgba(56, 189, 248, 0.18);
                                color: #fff;
                            }

                            /* Responsive: 1 nhân viên mỗi hàng trên màn nhỏ */
                            @media (max-width: 900px) {
                                .staff-card {
                                    width: 100%;
                                    max-width: 420px;
                                }

                                .staff-grid {
                                    gap: 20px;
                                }
                            }

                            @media (max-width: 600px) {
                                .staff-card {
                                    flex-direction: column;
                                    align-items: center;
                                    text-align: center;
                                    padding: 18px 8px;
                                }

                                .staff-card img {
                                    margin-bottom: 10px;
                                }

                                .staff-info h4 {
                                    font-size: 1.05rem;
                                }
                            }
                        </style>

                        <div class="container">
                            <h2>Danh sách nhân viên</h2>

                            <div class="staff-grid">
                                <?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="staff-card">
                                        <img src="<?php echo e(asset($staff->avatar)); ?>" alt="avatar">
                                        <div class="staff-info">
                                            <h4><?php echo e($staff->name); ?></h4>
                                            <p><strong>Email:</strong> <?php echo e($staff->email); ?></p>
                                            <p><strong>Giới tính:</strong> <?php echo e($staff->gender); ?></p>
                                            <p><strong>Ngày sinh:</strong> <?php echo e(\Carbon\Carbon::parse($staff->dob)->format('d/m/Y')); ?></p>
                                            <p><strong>Chức vụ:</strong> <?php echo e($staff->position ?? 'Chính thức'); ?></p>
                                            <p><strong>Lương:</strong> <?php echo e(number_format($staff->salary, 0, ',', '.')); ?> VNĐ</p>
                                            <a href="javascript:void(0)" class="btn-detail" data-id="<?php echo e($staff->id); ?>">Xem chi tiết</a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <!-- Modal chỉ đặt 1 lần ngoài vòng lặp -->
                        <div id="staffModal"
                            style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.35); align-items:center; justify-content:center; overflow:auto;">
                            <div id="staffModalContent"
                                style="background:#f1f5f9; border-radius:18px; max-width:960px; width:96vw; padding:0; position:relative; box-shadow:0 8px 32px rgba(0,0,0,0.18); overflow:hidden;">

                                <!-- Close Button -->
                                <button id="closeModal"
                                    style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:#555; cursor:pointer; z-index:1;">&times;</button>

                                <!-- Header -->
                                <div style="background:#2563eb; color:#fff; padding:20px 32px; font-size:1.3rem; font-weight:bold;">
                                    Hồ sơ nhân viên
                                </div>

                                <!-- Modal Body -->
                                <div id="modalBody" style="padding: 32px;">
                                    <!-- Nội dung sẽ được load ở đây bằng JS -->
                                </div>
                            </div>
                        </div>

                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.querySelectorAll('.btn-detail').forEach(btn => {
                        btn.addEventListener('click', function () {
                            const id = this.getAttribute('data-id');
                            fetch(`/admin/staff/${id}/detail`)
                                .then(response => response.json())
                                .then(staff => {
                                  document.getElementById('modalBody').innerHTML = `
            <div style="display: flex; flex-wrap: wrap; gap: 32px; align-items: flex-start; font-family: 'Segoe UI', Arial, sans-serif; padding: 20px; background-color: #f9fafb;">
                <!-- Sidebar -->
                <div style="flex: 0 0 260px; background: #ffffff; border-radius: 16px; padding: 24px 18px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <img src="${staff.avatar ? '/storage/' + staff.avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(staff.name)}"
                        alt="avatar"
                        style="width:110px; height:140px; object-fit:cover; border-radius:12px; border:2px solid #e2e8f0; margin-bottom:14px; background:#fff;">
                    <h2 style="margin: 8px 0 2px; color: #1f2937; font-size: 1.3rem; font-weight: 700;">${staff.name}</h2>
                    <div style="font-size:15px; color:#6b7280; margin-bottom:12px;">${staff.position ?? 'Nhân viên'}</div>
                    <div style="text-align:left; font-size:14.5px; color:#374151; line-height:1.7; margin-top: 8px;">
                        <p><i class="fa fa-phone"></i> <b>Điện thoại:</b> ${staff.phone ?? ''}</p>
                        <p><i class="fa fa-envelope"></i> <b>Email:</b> ${staff.email}</p>
                        <p><i class="fa fa-map-marker"></i> <b>Địa chỉ:</b> ${staff.address ?? ''}</p>
                        <p><b>Ngày sinh:</b> ${staff.dob ? (new Date(staff.dob)).toLocaleDateString('vi-VN') : ''}</p>
                        <p><b>Giới tính:</b> ${staff.gender ?? ''}</p>
                    </div>
                    <hr style="margin:18px 0; border-color: #e5e7eb;">
                    <div style="text-align:left; font-size:14.5px;">

                    </div>
                </div>

                <!-- Main content -->
                <div style="flex:1; min-width:260px;  background: #ffffff; border-radius: 16px; padding: 24px 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                    <h2 style="margin-bottom: 16px; color:#1e293b; font-size:1.25rem; font-weight:700;">Thông tin nhân viên</h2>
                    <div style="font-size:14.5px; color:#374151; margin-bottom:18px; line-height:1.8;">
                        <p><b>CMND/CCCD:</b> ${staff.citizen_id ?? ''}</p>
                        <p><b>Ngày cấp:</b> ${staff.issue_date ? (new Date(staff.issue_date)).toLocaleDateString('vi-VN') : ''}</p>
                        <p><b>Nơi cấp:</b> ${staff.issue_place ?? ''}</p>
                    </div>
                    <div style="margin:18px 0;">
                         <p><b>Loại hợp đồng:</b> ${staff.contract_type ?? ''}</p>
                        <p><b>Lương:</b> <span style="color:#2563eb; font-weight:500;">${staff.salary ? Number(staff.salary).toLocaleString('vi-VN') + ' VNĐ' : ''}</span></p>
                        <p><b>Ngày vào làm:</b> ${staff.start_date ? (new Date(staff.start_date)).toLocaleDateString('vi-VN') : ''}</p>
                    </div>
                    <div style="display:flex; gap:20px; margin-top:20px; flex-wrap:wrap;">
                        <div>
                            <h4 style="margin-bottom:6px; color:#2563eb; font-size:0.95rem;">Ảnh CCCD mặt trước</h4>
                            <img src="${staff.id_card_front ? '/storage/' + staff.id_card_front : 'https://via.placeholder.com/180x120?text=No+Image'}"
                                alt="CCCD trước"
                                style="width:180px; height:120px; object-fit:cover; border-radius:10px; border:1.5px solid #e2e8f0; box-shadow:0 2px 10px rgba(0,0,0,0.07);">
                        </div>
                        <div>
                            <h4 style="margin-bottom:6px; color:#2563eb; font-size:0.95rem;">Ảnh CCCD mặt sau</h4>
                            <img src="${staff.id_card_back ? '/storage/' + staff.id_card_back : 'https://via.placeholder.com/180x120?text=No+Image'}"
                                alt="CCCD sau"
                                style="width:180px; height:120px; object-fit:cover; border-radius:10px; border:1.5px solid #e2e8f0; box-shadow:0 2px 10px rgba(0,0,0,0.07);">
                        </div>
                    </div>
                </div>
            </div>
        `;

                                    document.getElementById('staffModal').style.display = 'flex';
                                });
                        });
                    });

                    document.getElementById('closeModal').onclick = function () {
                        document.getElementById('staffModal').style.display = 'none';
                    };
                    document.getElementById('staffModal').onclick = function (e) {
                        if (e.target === this) this.style.display = 'none';
                    };
                });
                document.getElementById('staffModal').classList.add('show');

                </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\F-poly\graduation_project\booking_laravel_project\booking_app - Copy\resources\views/admin/staff/staff_management.blade.php ENDPATH**/ ?>
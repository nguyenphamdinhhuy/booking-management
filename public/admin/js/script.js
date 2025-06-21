document.addEventListener("DOMContentLoaded", function () {
    // ==== FORM UPLOAD HÌNH ====
    const addRoomForm = document.getElementById('addRoomForm');
    const fileInput = document.getElementById('image');
    const fileUploadArea = document.querySelector('.file-upload-area');
    const imagePreview = document.getElementById('imagePreview');
    let selectedFile = null;

    if (fileInput && fileUploadArea && imagePreview) {
        fileUploadArea.addEventListener('click', () => fileInput.click());

        fileUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadArea.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', () => {
            fileUploadArea.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
            const file = e.dataTransfer.files[0];
            handleFile(file);
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });

        function handleFile(file) {
            if (file.type.startsWith('image/') && file.size <= 5 * 1024 * 1024) {
                selectedFile = file;
                displayImagePreview(file);
                updateFileInput();
            } else {
                alert(`File ${file.name}: Chỉ hỗ trợ file hình ảnh dưới 5MB`);
            }
        }

        function displayImagePreview(file) {
            imagePreview.innerHTML = '';
            const reader = new FileReader();
            reader.onload = (e) => {
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item';
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="preview-image">
                    <button type="button" class="remove-image" onclick="window.removeImage()">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                imagePreview.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        }

        window.removeImage = function () {
            selectedFile = null;
            imagePreview.innerHTML = '';
            fileInput.value = '';
        }

        function updateFileInput() {
            if (selectedFile) {
                const dt = new DataTransfer();
                dt.items.add(selectedFile);
                fileInput.files = dt.files;
            }
        }
    }

    // ==== FORM SUBMIT ====
    if (addRoomForm) {
        addRoomForm.addEventListener('submit', (e) => {
            

            const submitBtn = addRoomForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';
            submitBtn.disabled = true;

            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });

        // Reset button
        document.querySelector('button[type="reset"]').addEventListener('click', (e) => {
            e.preventDefault();
            if (confirm('Bạn có chắc muốn xóa tất cả dữ liệu đã nhập?')) {
                addRoomForm.reset();
                selectedFile = null;
                if (imagePreview) imagePreview.innerHTML = '';
            }
        });
    }

    // ==== FORMAT GIÁ ====
    const priceInput = document.getElementById('price_per_night');
    if (priceInput) {
        priceInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                const display = parseInt(value).toLocaleString('vi-VN');
                e.target.setAttribute('data-real-value', value);
            }
        });
    }

    // ==== BỘ LỌC ====
    let searchTimeout;

    window.searchRooms = function (searchValue = null) {
        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            const form = document.getElementById('filterForm');
            if (!form) return;

            const formData = new FormData(form);
            if (searchValue !== null) {
                formData.set('search', searchValue);
            }

            document.getElementById('loadingIndicator').style.display = 'block';
            document.getElementById('roomsTableBody').style.opacity = '0.5';

            const params = new URLSearchParams();
            for (let [key, value] of formData.entries()) {
                if (value !== '') params.append(key, value);
            }

            const routeUrl = document.getElementById('routeContainer')?.getAttribute('data-url') || '/admin/rooms';

            fetch(`${routeUrl}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateRoomsTable(data.rooms);
                    document.getElementById('roomCount').textContent = data.count;
                } else {
                    console.error(data.message);
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                document.getElementById('loadingIndicator').style.display = 'none';
                document.getElementById('roomsTableBody').style.opacity = '1';
            });
        }, 300);
    };

    window.applyFilters = function () {
        const form = document.getElementById('filterForm');
        if (form) form.submit();
    };

    window.clearFilters = function () {
        document.querySelectorAll('.filter-select').forEach(select => select.value = '');
        document.querySelector('.filter-input').value = '';
        const routeUrl = document.getElementById('routeContainer')?.getAttribute('data-url') || '/admin/rooms';
        window.location.href = routeUrl;
    };

    function updateRoomsTable(rooms) {
        const tbody = document.getElementById('roomsTableBody');
        if (!tbody) return;

        if (rooms.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="10" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-search" style="font-size: 48px;"></i><br>
                        Không tìm thấy phòng nào phù hợp với tiêu chí tìm kiếm.
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        rooms.forEach(room => {
            html += `
                <tr>
                    <td>${room.r_id}</td>
                    <td><strong>${room.name}</strong></td>
                    <td>
                        ${room.images ?
                            `<img src="/storage/${room.images}" onclick="window.showImageModal('/storage/${room.images}')" style="width:60px;height:45px;border-radius:4px;">` :
                            '<i style="color:#999;font-style:italic;">Không có ảnh</i>'
                        }
                    </td>
                    <td><strong>${room.formatted_price}</strong></td>
                    <td>${room.max_guests || 'N/A'}</td>
                    <td>${room.number_beds || 'N/A'}</td>
                    <td>
                        ${room.status == 1 ?
                            '<span class="status-badge status-active">Hoạt động</span>' :
                            '<span class="status-badge status-inactive">Không hoạt động</span>'
                        }
                    </td>
                    <td>${room.description ? room.description.substring(0, 50) + '...' : '<i style="color:#999;">Chưa có mô tả</i>'}</td>
                    <td>${room.formatted_created_at}</td>
                    <td>
                        <a href="/admin/rooms/view/${room.r_id}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                        <a href="/admin/rooms/edit/${room.r_id}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                        <a href="/admin/rooms/delete/${room.r_id}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    // ==== MODAL ẢNH ====
    window.showImageModal = function (src) {
        const modal = document.getElementById('imageModal');
        const img = document.getElementById('modalImage');
        if (modal && img) {
            img.src = src;
            modal.style.display = 'block';
        }
    };

    window.closeImageModal = function () {
        const modal = document.getElementById('imageModal');
        if (modal) modal.style.display = 'none';
    };

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            window.closeImageModal();
        }
    });

    // Gắn onchange cho các select filter
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', () => window.searchRooms());
    });
});

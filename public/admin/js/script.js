const addRoomForm = document.getElementById('addRoomForm');
const fileInput = document.getElementById('images');
const fileUploadArea = document.querySelector('.file-upload-area');
const imagePreview = document.getElementById('imagePreview');
let selectedFiles = [];

// File upload handling
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
    const files = Array.from(e.dataTransfer.files);
    handleFiles(files);
});

fileInput.addEventListener('change', (e) => {
    const files = Array.from(e.target.files);
    handleFiles(files);
});

function handleFiles(files) {
    files.forEach(file => {
        if (file.type.startsWith('image/') && file.size <= 5 * 1024 * 1024) {
            selectedFiles.push(file);
            displayImagePreview(file);
        } else {
            alert('Chỉ hỗ trợ file hình ảnh dưới 5MB');
        }
    });
}

function displayImagePreview(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
        const previewItem = document.createElement('div');
        previewItem.className = 'preview-item';
        previewItem.innerHTML = `
            <img src="${e.target.result}" alt="Preview" class="preview-image">
            <button type="button" class="remove-image" onclick="removeImage(this, '${file.name}')">
                <i class="fas fa-times"></i>
            </button>
        `;
        imagePreview.appendChild(previewItem);
    };
    reader.readAsDataURL(file);
}

function removeImage(button, fileName) {
    selectedFiles = selectedFiles.filter(file => file.name !== fileName);
    button.parentElement.remove();
}

// Form validation and submission
addRoomForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const formData = new FormData(addRoomForm);
    
    // Add selected files to form data
    selectedFiles.forEach(file => {
        formData.append('room_images[]', file);
    });

    // Basic validation
    const name = formData.get('name');
    const price = formData.get('price_per_night');

    if (!name || !price) {
        alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
        return;
    }

    if (price <= 0) {
        alert('Giá phòng phải lớn hơn 0!');
        return;
    }

    // Simulate form submission
    const submitBtn = addRoomForm.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';
    submitBtn.disabled = true;

    // Simulate API call
    setTimeout(() => {
        alert('Thêm phòng thành công!');
        addRoomForm.reset();
        imagePreview.innerHTML = '';
        selectedFiles = [];
        
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 2000);

    console.log('Form data:', Object.fromEntries(formData));
    console.log('Selected files:', selectedFiles);
});

// Price formatting
const priceInput = document.getElementById('price_per_night');
priceInput.addEventListener('input', (e) => {
    let value = e.target.value.replace(/\D/g, '');
    if (value) {
        // Format number with thousand separators
        e.target.value = parseInt(value).toLocaleString('vi-VN');
    }
});

priceInput.addEventListener('blur', (e) => {
    // Remove formatting for actual value
    e.target.value = e.target.value.replace(/\D/g, '');
});

// Custom Scrollbar
const sidebarStyle = document.createElement('style');
sidebarStyle.textContent = `
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar::-webkit-scrollbar-track {
        background: #34495e;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background: #3498db;
        border-radius: 3px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: #2980b9;
    }
`;
document.head.appendChild(sidebarStyle);
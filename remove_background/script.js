const imageInput = document.getElementById('imageInput');
const previewImage = document.getElementById('previewImage');
const removeBgBtn = document.getElementById('removeBgBtn');
const downloadBtn = document.getElementById('downloadBtn');

let uploadedFile = null;
let processedImageUrl = null;

imageInput.addEventListener('change', function() {
  const file = this.files[0];
  if (file) {
    uploadedFile = file;
    const reader = new FileReader();
    reader.onload = function(e) {
      previewImage.src = e.target.result;
      previewImage.style.display = 'block';
      downloadBtn.style.display = 'none';
    };
    reader.readAsDataURL(file);
  }
});

removeBgBtn.addEventListener('click', async function() {
  if (!uploadedFile) {
    alert('UPLOAD AN IMAGE FIRST!');
    return;
  }

  removeBgBtn.textContent = 'PROCESSING...';
  removeBgBtn.disabled = true;

  const formData = new FormData();
  formData.append('image_file', uploadedFile);
  formData.append('size', 'auto');

  try {
    const response = await fetch('https://api.remove.bg/v1.0/removebg', {
      method: 'POST',
      headers: {
        'X-Api-Key': 'juu9uU1RtjiifxpXsyoqrYEU'
      },
      body: formData
    });

    if (!response.ok) throw new Error('API ERROR');

    const blob = await response.blob();
    processedImageUrl = URL.createObjectURL(blob);

    previewImage.src = processedImageUrl;
    previewImage.style.display = 'block';
    downloadBtn.style.display = 'inline-block';
  } catch (error) {
    alert('FAILED TO REMOVE BACKGROUND: ' + error.message);
  } finally {
    removeBgBtn.textContent = 'REMOVE BACKGROUND';
    removeBgBtn.disabled = false;
  }
});

downloadBtn.addEventListener('click', function() {
  if (!processedImageUrl) return;
  const link = document.createElement('a');
  link.href = processedImageUrl;
  link.download = 'no-background.png';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
});

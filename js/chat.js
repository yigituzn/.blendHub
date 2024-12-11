async function updateChatHeader() {
    const response = await fetch('get_user_role.php');
    const { role } = await response.json(); // Kullanıcı rolünü al
    const chatHeader = document.querySelector('.chat-header span');
    chatHeader.textContent = role === 'mentor' 
      ? 'Kullanıcılar ile Canlı Sohbet' 
      : 'Mentörler ile Canlı Sohbet';
  }
  
  // Sohbet widget'ı ve listeyi yükle
  document.addEventListener('DOMContentLoaded', () => {
    updateChatHeader(); // Başlığı güncelle
    loadChatList(); // Sohbet listesi yüklensin
  });
  
  let chatInterval;
  
  async function loadChatList() {
    const response = await fetch('get_chat_list.php');
    const chatList = await response.json();
    const mentorList = document.getElementById('mentor-list');
    mentorList.innerHTML = '';
  
    chatList.forEach(person => {
      const div = document.createElement('div');
      div.className = 'mentor-item';
      div.innerHTML = person.profile_picture
        ? `<img src="data:image/jpeg;base64,${person.profile_picture}" alt="${person.username}">`
        : `<img src="images/dprofile.jpg" alt="Default Profile Picture">`;
  
      div.innerHTML += `<span>${person.username}</span>`;
      div.innerHTML += `<div class="status-indicator" style="background-color: ${
    (person.is_online == 1) ? 'green' : 'gray'
  };"></div>`;
      div.onclick = () => openChat(person.user_id, person.username);
      mentorList.appendChild(div);
    });
  }
  
  // Sohbet widget'ını aç/kapa
  function toggleChat() {
    const chatWidget = document.querySelector('.chat-widget');
    const chatToggle = document.querySelector('.chat-toggle');
    const chatBox = document.getElementById('chat-box');
    const mentorList = document.getElementById('mentor-list');
    const backBtn = document.getElementById('back-btn');
  
    // Sohbet widget'ını aç/kapa
    if (chatWidget.style.display === 'flex') {
      chatWidget.style.display = 'none'; // Kapat
      chatToggle.style.display = 'flex'; // Balon görünsün
      chatBox.classList.remove('active'); // Sohbet ekranını gizle
      mentorList.style.display = 'block'; // Mentör listesi gösterilsin
      backBtn.style.display = 'none'; // Geri butonunu gizle
  
      // Mesaj yenileme intervalini durdur
      if (chatInterval) {
        clearInterval(chatInterval);
      }
    } else {
      chatWidget.style.display = 'flex'; // Aç
      chatToggle.style.display = 'none'; // Balon gizlensin
    }
  }
  
  // Sohbet ekranını aç ve mesajları yükle
  async function openChat(mentorId, mentorName) {
    const chatBox = document.getElementById('chat-box');
    const messagesDiv = document.getElementById('messages');
    const mentorList = document.getElementById('mentor-list');
    const backBtn = document.getElementById('back-btn');
  
    mentorList.style.display = 'none'; // Mentör listesini gizle
    chatBox.dataset.mentorId = mentorId;
    chatBox.classList.add('active'); // Sohbet ekranını görünür yap
    backBtn.style.display = 'inline-block'; // Geri butonunu göster
    messagesDiv.innerHTML = `<h3>${mentorName} ile sohbet</h3>`; // Başlığı güncelle
  
    await loadMessages(mentorId); // Mesajları yükle
  
    // Mesajları otomatik yenile
    if (chatInterval) {
      clearInterval(chatInterval); // Daha önceki interval varsa temizle
    }
    chatInterval = setInterval(() => {
      loadMessages(mentorId);
    }, 2000);
  
  }
  
  // Sohbet listesini geri yükle
  function goBackToList() {
    const chatBox = document.getElementById('chat-box');
    const mentorList = document.getElementById('mentor-list');
    const backBtn = document.getElementById('back-btn');
  
    chatBox.classList.remove('active'); // Sohbet ekranını gizle
    mentorList.style.display = 'block'; // Liste ekranını göster
    backBtn.style.display = 'none'; // Geri butonunu gizle
  
    if (chatInterval) {
      clearInterval(chatInterval); // Mesaj yenileme intervalini durdur
    }
  }
  
  // Sohbet ekranını kapat
  function closeChat() {
    const chatBox = document.getElementById('chat-box');
    const mentorList = document.getElementById('mentor-list');
    chatBox.classList.remove('active');
    mentorList.style.display = 'block'; // Mentör listesini tekrar göster
    if (chatInterval) {
      clearInterval(chatInterval); // Interval durdur
    }
  }
  
  // Mesajları yükle
  async function loadMessages(mentorId) {
    const response = await fetch(`get_messages.php?mentorId=${mentorId}`);
    const messages = await response.json();
    const messagesDiv = document.getElementById('messages');
    messagesDiv.innerHTML = ''; // Önceki mesajları temizle
  
    // Son mesajı bulmak için toplam mesaj sayısını kontrol edin
    const totalMessages = messages.length;
  
    messages.forEach((message, index) => {
      const isCurrentUser = message.is_current_user;
      const div = document.createElement('div');
      div.className = isCurrentUser == 1 ? 'message-right' : 'message-left';
  
      // Mesaj içeriğini ekle
      if (isCurrentUser == 1) {
        div.innerHTML = `<span>${message.message}</span>`;
      } else {
        div.innerHTML = `<strong>${message.sender_name}:</strong> <span>${message.message}</span>`;
      }
  
      messagesDiv.appendChild(div);
    });
  }
  
  // Mesaj gönderme
  async function sendMessage() {
    const mentorId = document.getElementById('chat-box').dataset.mentorId;
    const messageInput = document.getElementById('message-input');
    const message = messageInput.value;
    if (message.trim()) {
      const response = await fetch('send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ mentorId, message }),
      });
      const result = await response.json();
      if (result.success) {
        messageInput.value = ''; // Mesajı temizle
        loadMessages(mentorId); // Mesajları yeniden yükle
      } else {
        alert('Mesaj gönderilirken bir hata oluştu.');
      }
    }
  }

  setInterval(async () => {
    await fetch('update_activity.php', { method: 'POST' });
  }, 12000);

document.addEventListener('DOMContentLoaded', function() {

    function loadContacts() {
        // Fazer requisição HTTP para carregar os contatos do back-end
        fetch('../templates/messages/get_contacts.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao carregar contatos: ' + response.statusText);
                }
                return response.json();
            })
            .then(contacts => {
                // Atualizar a lista de contatos com os dados recebidos do servidor
                // Implementar lógica para atualizar a lista de contatos na interface
            })
            .catch(error => {
                console.error(error);
            });
    }

    function loadMessages() {
        console.log('Carregando mensagens...');

        document.addEventListener('DOMContentLoaded', function() {
            const ownerId = document.getElementById('receiver-id').value;
            const itemId = document.getElementById('item-id').value;
        
            fetch(`/../templates/messages/get_messages.php?owner_id=${ownerId}&item_id=${itemId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao carregar mensagens: ' + response.statusText);
                    }
                    return response.json(); 
                })
                .then(messages => {
                    console.log('Mensagens recebidas:', messages);

                    drawChat(ownerId, itemId, messages);
                })
                .catch(error => {
                    console.error('Erro ao carregar mensagens:', error);
                });
        });
    }
    
    
    function drawChat(ownerId, itemId, messages) {
        const chatContainer = document.getElementById('chat-container');
        chatContainer.innerHTML = ''; // Limpa o conteúdo atual do contêiner do chat
    
        // Percorre todas as mensagens e adiciona cada uma ao contêiner do chat
        messages.forEach(message => {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.classList.add(message.senderId === ownerId ? 'sent' : 'received');
    
            const senderName = message.senderId === ownerId ? "Você" : message.senderName; // Adapte para o nome do remetente
            const messageContent = document.createElement('p');
            messageContent.textContent = `${senderName}: ${message.messageText}`;
    
            const messageDate = document.createElement('span');
            messageDate.textContent = message.sendDate;
    
            messageDiv.appendChild(messageContent);
            messageDiv.appendChild(messageDate);
            chatContainer.appendChild(messageDiv);
        });
    }
    
    function sendMessage(receiverId, message) {
        const formData = new FormData();
        formData.append('receiverId', receiverId);
        formData.append('message-input', message);
        formData.append('item-id', document.getElementById('item-id').value);
        fetch('../templates/messages/send_message.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao enviar mensagem: ' + response.statusText);
            }
            // Verifica se a resposta é JSON
            return response.json();
        })
        .then(data => {
            if (data.success) {
                console.log(data.message); // Exibe mensagem de sucesso no console
                // Se a mensagem foi enviada com sucesso, recarrega as mensagens
                loadMessages(); // Chamada para recarregar as mensagens
            } else {
                console.error(data.error); // Exibe mensagem de erro no console
            }
        })
        .catch(error => {
            console.error('Erro ao enviar mensagem:', error);
        });
    }


    document.getElementById('message-form').addEventListener('submit', function(event) {
        event.preventDefault(); 

        const formData = new FormData(this);
        const message = formData.get('message-input');
        const receiverId = document.getElementById('receiver-id').value;

        if (message.trim() !== '') {
            sendMessage(receiverId, message);
            document.getElementById('message-input').value = '';
        } else {
            alert('Por favor, insira uma mensagem antes de enviar.');
        }
    });


    setInterval(loadMessages, 5000);

    
    
});


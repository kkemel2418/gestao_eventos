// Função para tratar resposta de sucesso
function handleSuccess(data, xhr) {
    if (xhr.status === 200) {
        console.log('Dados recebidos com sucesso:', data);
        // Aqui você pode adicionar lógica para atualizar o front-end com os dados recebidos
    } else if (xhr.status === 201) {
        console.log('Recurso criado com sucesso:', data);
    }
}

// Função para tratar erro 404 - Not Found
function handleNotFound() {
    alert('Recurso não encontrado. Verifique a URL ou tente novamente mais tarde.');
}

// Função para tratar erro 500 - Internal Server Error
function handleServerError() {
    alert('Desculpe, ocorreu um erro no servidor. Tente novamente mais tarde.');
}

// Função para tratar erros de requisições gerais
function handleError(xhr, textStatus, errorThrown) {
    if (xhr.status === 404) {
        handleNotFound();
    } else if (xhr.status === 500) {
        handleServerError();
    } else if (xhr.status === 400) {
        alert('Erro de requisição inválida (400): ' + errorThrown);
    } else if (xhr.status === 401) {
        alert('Você não tem permissão para acessar este recurso (401).');
    } else if (xhr.status === 403) {
        alert('Acesso proibido (403). Verifique suas permissões.');
    } else {
        alert('Erro desconhecido: ' + errorThrown);
    }
}

// Função para realizar requisições AJAX personalizadas
function apiRequest(url, method, data = null, successCallback, errorCallback) {
    $.ajax({
        url: url,
        method: method,
        data: data,
        contentType: "application/json", // Definindo tipo de conteúdo para JSON
        dataType: "json",               // Esperando resposta como JSON
        success: function(response, textStatus, xhr) {
            successCallback(response, xhr); // Chama a função de sucesso
        },
        error: function(xhr, textStatus, errorThrown) {
            errorCallback(xhr, textStatus, errorThrown); // Chama a função de erro
        }
    });
}

// Função para enviar dados no formato JSON
function sendData(url, data, successCallback, errorCallback) {
    apiRequest(url, 'POST', JSON.stringify(data), successCallback, errorCallback);
}

// Função para obter dados (GET)
function fetchData(url, successCallback, errorCallback) {
    apiRequest(url, 'GET', null, successCallback, errorCallback);
}

// Função para atualizar dados (PUT)
function updateData(url, data, successCallback, errorCallback) {
    apiRequest(url, 'PUT', JSON.stringify(data), successCallback, errorCallback);
}

// Função para deletar dados (DELETE)
function deleteData(url, successCallback, errorCallback) {
    apiRequest(url, 'DELETE', null, successCallback, errorCallback);
}

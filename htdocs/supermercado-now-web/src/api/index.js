import axios from 'axios'
import store from '../store'

const api = axios.create({
    baseURL: 'http://127.0.0.1:8000/api',
});
let isAlreadyFetchingAccessToken = false
let subscribers = []

function onAccessTokenFetched(token) {
    subscribers = subscribers.filter(callback => callback(token))
}

function addSubscriber(callback) {
    subscribers.push(callback)
}

// INTERCEPTA O ENVIO PARA POR O TOKEN
api.interceptors.request.use(config => {
    const token = localStorage.getItem(store.state.TOKEN_KEY);
    if (token) { // Poe o token em todas as requisicoes
        config.headers.Authorization = `Bearer ${token}`;
    }
    config.withCredentials = true;
    return config;
});

// INTERCEPTA A RESPOSTA PARA VERIFICAR FALHAS E REFAZER REQUISICOES QUANDO NECESSARIO
api.interceptors.response.use(response => { // Em caso de sucesso, retorna a resposta normalmente
    return response;
}, async error => { // em caso de erro, tenta atualizar o token
    const { config, response: { status, data } } = error // Pega informacoes do erro
    const originalRequest = config
    if (status === 401) { // Se o erro for 401 (Autenticacao)
        //Guarda as requiscoes que falharam num array para serem executadas novamente apos a atualizacao do token
        const retryOriginalRequest = new Promise((resolve) => {
            addSubscriber(token => {
                originalRequest.headers.Authorization = `Bearer ${token}`
                resolve(api.request(originalRequest))
            })
        })

        if (!isAlreadyFetchingAccessToken) { // tenta pegar um novo token com o refresh_token
            isAlreadyFetchingAccessToken = true
            const response = await api.post(`/login/refresh`)
            const token = response.data.access_token
            localStorage.setItem(store.state.TOKEN_KEY, token)
            isAlreadyFetchingAccessToken = false
            onAccessTokenFetched(token) // refaz as requisicoes que falharam
        } else { // Se o refresh token tiver expirado, sai da aplicação
            if (data.grant_type == 'refresh_token') {
                isAlreadyFetchingAccessToken = false
                store.commit('logout');
                return Promise.reject(error)
            }
        }
        return retryOriginalRequest
    }
    return Promise.reject(error)
});

export default api;
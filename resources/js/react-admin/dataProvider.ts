// in resources/js/react-admin/dataProvider.ts
import { fetchUtils } from 'react-admin';
import jsonServerProvider from "ra-data-json-server";
import { stringify } from 'query-string';

const httpClient = (url, options = {}) => {
    if (!options.headers) {
        options.headers = new Headers({ Accept: 'application/json' });
    }
    const token = localStorage.getItem('auth') ? JSON.parse(localStorage.getItem('auth')) : undefined
    if (token) {
        options.headers.set('Authorization', `${token.token_type} ${token.access_token}`);
    }
    return fetchUtils.fetchJson(url, options);
};

const dataProvider = jsonServerProvider(
    import.meta.env.VITE_JSON_SERVER_URL,
    httpClient
);

const originalDataProvider = jsonServerProvider(
    import.meta.env.VITE_JSON_SERVER_URL,
    httpClient
);

const apiUrl = `${import.meta.env.VITE_JSON_SERVER_URL}`;

dataProvider.getMany = (resource, params) => {
    const query = {
        id: params.ids,
    };
    const url = `${apiUrl}/${resource}?${stringify(query, {arrayFormat: 'bracket'})}`;
    return httpClient(url).then(({ json }) => ({ data: json }));
};

dataProvider.createToken = (email, password) => {
    return httpClient(`${apiUrl}/tokens`, {
        method: 'POST',
        body: JSON.stringify({ email, password }),
        headers: new Headers({ 'Content-Type': 'application/json' }),
    });
};

dataProvider.deleteToken = () => {
    return httpClient(`${apiUrl}/tokens`, {
        method: 'DELETE',
        headers: new Headers({ 'Content-Type': 'application/json' }),
    });
};

dataProvider.getIdentity = () => {
    return httpClient(`${apiUrl}/user`, {
        method: 'GET',
        headers: new Headers({ 'Content-Type': 'application/json' }),
    });
};

dataProvider.postLogin = (email, password) => {
    return httpClient(`${apiUrl}/login`, {
        method: 'POST',
        body: JSON.stringify({ email, password }),
        headers: new Headers({ 'Content-Type': 'application/json' }),
    });
};

dataProvider.postLogout = () => {
    return httpClient(`${apiUrl}/logout`, {
        method: 'POST',
        headers: new Headers({ 'Content-Type': 'application/json' }),
    });
};

dataProvider.update = (resource, params) => {

    let result;
    switch (resource){
        case 'proyectos':
            result = updateInsideLogic('fichero', resource, params);
            break;
        case 'curriculos':
            result = updateInsideLogic('pdf_curriculum',resource,params);
            break;
        default :
            result = updateInsideLogic('none',resource,params);
            break;
    }

    return result;
}

function updateInsideLogic($_strAppend, resource, params){
    if (params.data.attachments && $_strAppend !== 'none'){

        let formData = new FormData();
        for (const property in params.data) {
            formData.append(`${property}`, `${params.data[property]}`);
        }

        formData.append($_strAppend, params.data.attachments.rawFile);

        formData.append('_method', 'PUT')

        const url = `${apiUrl}/${resource}/${params.id}`
        return httpClient(url, {
            method: 'POST',
            body: formData,
        })
        .then(json => {
            return {
                ...json,
                data: json.json
            }
        })

    }else{
        return originalDataProvider.update(resource, params);
    }
}

export { dataProvider };

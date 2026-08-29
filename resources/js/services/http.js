const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

export const jsonRequest = async (url, options = {}) => {
    const response = await fetch(url, {
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            ...options.headers,
        },
        ...options,
    });

    const payload = await response.json().catch(() => ({}));

    if (!response.ok) {
        const error = new Error(payload.message || 'Request failed.');
        error.response = payload;
        error.status = response.status;
        throw error;
    }

    return payload;
};

export const formRequest = async (url, formData, options = {}) => {
    const response = await fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
            ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            ...options.headers,
        },
        body: formData,
        ...options,
    });

    const payload = await response.json().catch(() => ({}));

    if (!response.ok) {
        const error = new Error(payload.message || 'Request failed.');
        error.response = payload;
        error.status = response.status;
        throw error;
    }

    return payload;
};

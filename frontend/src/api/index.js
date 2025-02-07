import apiInstance from "./instance";

const token = () => {
  const session = localStorage.getItem("user");
  if (session) {
    try {
      const parsedSession = JSON.parse(session);
      return parsedSession.access_token || "";
    } catch (error) {
      console.error("Error parsing user session:", error);
    }
  }
  return "";
};

export const login = async (data = {}) => {
  try {
    const response = await apiInstance.post("/login", data);
    return response;
  } catch (error) {
    return error;
  }
};

export const logoutSession = async () => {
  try {
    const response = await apiInstance.post(
      "/logout",
      {},
      {
        Authorization: `Bearer ${token()}`,
      }
    );
    return response;
  } catch (error) {
    return error;
  }
};

export const tickets = async (params = {}) => {
  try {
    const response = await apiInstance.get("/tickets", params, {
      Authorization: `Bearer ${token()}`,
    });
    return response;
  } catch (error) {
    return error;
  }
};

export const create = async (data) => {
  try {
    const response = await apiInstance.post("/tickets", data, {
      Authorization: `Bearer ${token()}`,
    });
    return response;
  } catch (error) {
    return error;
  }
};

export const show = async (id) => {
  try {
    const response = await apiInstance.get(
      `/tickets/${id}`,
      {},
      {
        Authorization: `Bearer ${token()}`,
      }
    );
    return response;
  } catch (error) {
    return error;
  }
};

export const update = async (id, data) => {
  try {
    const response = await apiInstance.put(`/tickets/${id}`, data, {
      Authorization: `Bearer ${token()}`,
    });
    return response;
  } catch (error) {
    return error;
  }
};

export const destroy = async (id) => {
  try {
    const response = await apiInstance.delete(`/tickets/${id}`, {
      Authorization: `Bearer ${token()}`,
    });
    return response;
  } catch (error) {
    return error;
  }
};

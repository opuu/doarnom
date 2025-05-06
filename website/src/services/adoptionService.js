import api from "./api";

export function listAdoptions() {
  return api.get("/adoptions").then((res) => res.data.payload.list);
}

export function getAdoption(id) {
  return api.get(`/adoptions/${id}`).then((res) => res.data.payload);
}

export function createAdoption(payload) {
  return api.post("/adoptions", payload).then((res) => res.data.payload);
}

export function updateAdoption(id, payload) {
  return api.patch(`/adoptions/${id}`, payload).then((res) => res.data.payload);
}

export function deleteAdoption(id) {
  return api.delete(`/adoptions/${id}`).then((res) => res.data.payload);
}

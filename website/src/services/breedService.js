import api from "./api";

export function listBreeds() {
  return api.get("/breeds").then((res) => res.data.payload.list);
}

export function getBreed(id) {
  return api.get(`/breeds/${id}`).then((res) => res.data.payload);
}

export function createBreed(payload) {
  return api.post("/breeds", payload).then((res) => res.data.payload);
}

export function updateBreed(id, payload) {
  return api.patch(`/breeds/${id}`, payload).then((res) => res.data.payload);
}

export function deleteBreed(id) {
  return api.delete(`/breeds/${id}`).then((res) => res.data.payload);
}

import api from "./api";

export function listPets() {
  return api.get("/pets").then((res) => res.data.payload.list);
}

export function getPet(id) {
  return api.get(`/pets/${id}`).then((res) => res.data.payload);
}

export function createPet(payload) {
  return api.post("/pets", payload).then((res) => res.data.payload);
}

// use PATCH per Insomnia
export function updatePet(id, payload) {
  return api.patch(`/pets/${id}`, payload).then((res) => res.data.payload);
}

export function deletePet(id) {
  return api.delete(`/pets/${id}`).then((res) => res.data.payload);
}

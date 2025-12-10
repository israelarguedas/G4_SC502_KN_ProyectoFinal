import { handleSearch } from "./handlers/searchHandler.js";

document.addEventListener("DOMContentLoaded", async function () {
  console.log("Tico Trips App Loaded - DOMContentLoaded");

  try {
    handleSearch();
  } catch (error) {
    console.log(error);
  }
});

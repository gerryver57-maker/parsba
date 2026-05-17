
const container = document.getElementById("container");
const registerBtn = document.getElementById("register");
const loginBtn = document.getElementById("login");
const daftar = document.getElementById("daftar");

            registerBtn.addEventListener("click", () => {
                container.classList.add("active");
            });
            loginBtn.addEventListener("click", () => {
                container.classList.remove("active");
            });
            daftar.addEventListener("click", () => {
                container.classList.add("active");
            });

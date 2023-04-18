<template>
    <div id="sideBar">
        <div>
            <h3>MAIN NAVIGATION</h3>
        </div>
        <div class="side-bar">
            <RouterLink class="nav-link" to="/"> <span>Client Side</span>
            </RouterLink>
            <RouterLink class="nav-link" to="/admin"><span>Admin</span>
            </RouterLink>
        </div>
    </div>
</template>

<style scoped>
#sideBar {
    margin-top: 40px
}

.nav-link {
    padding: 10px 0px 10px 20px;
    margin-top: 20px
}

span {
    font-family: 'Poppins';
    margin-left: 10px;
    font-size: 20px;
    font-weight: 600;
}

.side-menu a {
    text-decoration: none;
}

a:hover {
    background-color: #5477ec;
    opacity: 0.7;
    color: #FFFFFF;
    border-right: 5px solid #2c4aaf;
}

a.router-link-exact-active {
    color: #FFFFFF;
    background-color: #5477ec;
    border-right: 5px solid #2c4aaf;
}

h3 {
    font-size: 25px;
    color: #2c4aaf;
    font-weight: 600;
    margin-left: 30px;
}
</style>
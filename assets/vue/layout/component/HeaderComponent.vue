<template>
    <div>
        <mdb-navbar
                v-if="$store.state.doc.docs"
                id="main-navbar"
                dark
                position="top"
                color="stylish"
                style="z-index: 1049"
                expand="xl"
                scrolling
                :scrollingOffset="20"
        >
            <mdb-navbar-brand to="/" waves class="font-weight-bold"
            >MDB Vue</mdb-navbar-brand
            >
            <mdb-navbar-toggler>
                <mdb-navbar-nav right>
                    <mdb-nav-item to="/css">
                        <strong>CSS</strong>
                    </mdb-nav-item>
                    <mdb-tooltip>
                        <span slot="tip">PRO</span>
                        <mdb-nav-item
                                slot="reference"
                                href="https://mdbootstrap.com/products/vue-ui-kit/"
                                newTab
                                far
                                icon="gem"
                        ></mdb-nav-item>
                    </mdb-tooltip>
                    <mdb-tooltip>
                        <span slot="tip">FREE</span>
                        <mdb-nav-item
                                slot="reference"
                                href="https://mdbootstrap.com/docs/vue/getting-started/download/"
                                newTab
                                icon="download"
                        ></mdb-nav-item>
                    </mdb-tooltip>
                </mdb-navbar-nav>
            </mdb-navbar-toggler>
        </mdb-navbar>
    </div>
</template>

<script>
    import {
        mdbNavbar,
        mdbNavItem,
        mdbNavbarNav,
        mdbNavbarToggler,
        mdbNavbarBrand,
        mdbTooltip
    } from "mdbvue";
    export default {
        name: "HeaderComponent",
        components: {
            mdbNavbar,
            mdbNavItem,
            mdbNavbarNav,
            mdbNavbarToggler,
            mdbNavbarBrand,
            mdbTooltip
        }
    }
</script>

<style scoped>

</style>
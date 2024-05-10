<template>
  <v-navigation-drawer expand-on-hover rail>
    <v-list>
      <v-list-item
        prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
        subtitle="sandra_a88@gmailcom"
        title="Sandra Adams"
      ></v-list-item>
    </v-list>

    <v-divider></v-divider>

    <v-list density="compact" nav>
      <v-list-item
        prepend-icon="mdi-home"
        title="Home"
        :to="{ name: 'Home' }"
      ></v-list-item>
      <v-list-item
        prepend-icon="mdi-account"
        title="Shared with me"
        value="shared"
      ></v-list-item>
      <v-list-item
        prepend-icon="mdi-logout"
        title="Logout"
        link
      ></v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>
<script></script>

<template>
  <v-overlay
    :model-value="isFetching"
    class="align-center justify-center"
    persistent
  >
    <v-progress-circular
      color="primary"
      size="64"
      indeterminate
    ></v-progress-circular>
  </v-overlay>

  <v-snackbar v-model="registeredWelcomeSnackBar" :timeout="2000">
    Registered successfully
  </v-snackbar>

  <v-snackbar v-model="publishSuccessful" :timeout="2000">
    Published successfully
  </v-snackbar>

  <v-container>
    <div class="d-flex justify-space-between">
      <div>
        <h1>Your translations</h1>
      </div>
      <div class="d-flex w-50">
        <v-text-field
          v-model="searchTitle"
          hide-details="auto"
          label="Search by title"
          @update:model-value="resetPage"
          clearable
        ></v-text-field>
      </div>
      <div>
        <AddTranslationDialog>
          <template v-slot:activator="{ onClick }">
            <v-btn
              @click="onClick"
              class="ml-4 h-100"
              prepend-icon="mdi-plus"
              color="blue"
              >Add new</v-btn
            >
          </template>
        </AddTranslationDialog>
      </div>
    </div>
  </v-container>

  <v-container>
    <v-row align="start">
      <v-col v-for="n in data?.translations" :key="n.id" md="4">
        <v-card
          class="mx-auto"
          :to="{ name: 'TranslationsEdit', params: { id: n.id } }"
        >
          <v-img
            height="400px"
            :src="`/storage/${n.image_name.filename}`"
            cover
          ></v-img>

          <v-card-title>
            {{ n.title }}
          </v-card-title>

          <v-menu z-index="10">
            <template v-slot:activator="{ props }">
              <v-list-item
                @click.prevent
                append-icon="mdi-chevron-down"
                lines="two"
                v-bind="props"
              >
                <v-list-item-title>
                  {{ new Date(n.updated_at).toLocaleString() }} &bull;
                  <span v-if="n.public === 1" class="text-green"> public </span>
                  <span v-else class="text-grey"> private </span>
                </v-list-item-title>
              </v-list-item>
            </template>
            <v-list>
              <v-list-item value="publish" @click="mutate(n.id)">
                <v-list-item-title>{{
                  n.public === 1 ? 'Unpublish' : 'Publish'
                }}</v-list-item-title>
              </v-list-item>
              <DeleteTranslationConfirmDialog
                :id="n.id"
                @on-success="onDeleteSuccess(n.id)"
              >
                <template v-slot:activator="{ onClick }">
                  <v-list-item @click="onClick" link>
                    <v-list-item-title class="text-red"
                      >Delete</v-list-item-title
                    >
                  </v-list-item>
                </template>
              </DeleteTranslationConfirmDialog>
            </v-list>
          </v-menu>
        </v-card>
      </v-col>
      <v-responsive width="100%"></v-responsive>
    </v-row>
  </v-container>

  <div v-if="data?.translations && data.translations.length > 0">
    <v-divider></v-divider>
    <v-pagination
      v-model="currentPage"
      :length="data?.last_page"
      @update:model-value="changePage"
    ></v-pagination>
  </div>
  <div v-else class="text-center text-h4 font-weight-bold">
    No translations found.
  </div>
</template>
<script setup lang="ts">
import {
  usePublishTranslationMutation,
  useSearchTranslationByTitleQuery,
} from '@/api/translations/hooks.ts';
import _ from 'lodash';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import AddTranslationDialog from '@/components/AddTranslationDialog.vue';
import DeleteTranslationConfirmDialog from '@/components/DeleteTranslationConfirmDialog.vue';
import { useQueryClient } from '@tanstack/vue-query';
import { UserTranslationsRequest } from '@/types/requests/TranslationRequest.ts';
import { Translation } from '@/types/Translation.ts';

const searchTitle = defineModel('searchTitle', { default: '' });
const currentPage = defineModel('currentPage', { default: 1 });
const publishSuccessful = defineModel('publishSuccessful', { default: false });

const { data, isFetching, refetch } = useSearchTranslationByTitleQuery(
  searchTitle,
  currentPage
);

const { mutate } = usePublishTranslationMutation(() => {
  refetch();
});

const route = useRoute();
const registeredWelcomeSnackBar = ref(
  route.query.registeredSnackBar === 'true'
);
const queryClient = useQueryClient();

const onDeleteSuccess = (id: number) => {
  queryClient.setQueryData(
    ['translationsList', searchTitle.value],
    (oldData: UserTranslationsRequest) => {
      return {
        ...oldData,
        translations: oldData.translations.filter(
          (item: Translation) => item.id !== id
        ),
      };
    }
  );
};

const resetPage = _.debounce(() => {
  currentPage.value = 1;
  refetch();
}, 500);

onMounted(() => {
  refetch();
});

const changePage = () => {
  refetch();
};
</script>

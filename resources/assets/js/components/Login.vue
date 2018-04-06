<template>
	<v-content>
		<v-container fluid fill-height>
			<v-layout align-center justify-center>
				<v-flex xs12 sm8 md4>
					<v-container fluid grid-list-md>
						<v-layout row wrap>
							<v-flex>
								<v-card-media :contain="true" :src="'/assets/img/prem-league.png'" height="225"></v-card-media>
							</v-flex>
							<v-flex>
								<v-card-media :contain="true" :src="'/assets/img/efl.png'" height="225"></v-card-media>
							</v-flex>
							<v-flex>
								<v-card-media :contain="true" :src="'/assets/img/efl-league-one.png'" height="225"></v-card-media>
							</v-flex>
							<v-flex>
								<v-card-media :contain="true" :src="'/assets/img/efl-league-two.png'" height="225"></v-card-media>
							</v-flex>
						</v-layout>
					</v-container>

					<v-card class="elevation-12">
						
					</v-card>
					<v-card class="elevation-12">
						<v-form ref="form" method="post" action="/login" lazy-validation v-model="valid">
							<v-toolbar dark color="green lighten-1">
								<v-toolbar-title>Login form</v-toolbar-title>
								<v-spacer></v-spacer>
							</v-toolbar>
							<v-card-text>
								<input type="hidden" name="_token" :value="csrf" />
								<v-text-field prepend-icon="person" name="login" label="Login" type="text"></v-text-field>
								<v-text-field prepend-icon="lock" name="password" label="Password" id="password" type="password"></v-text-field>
							</v-card-text>
							<v-card-actions>
								<v-spacer></v-spacer>
								<v-btn color="green lighten-1 theme--dark" type="submit">Login</v-btn>
							</v-card-actions>
						</v-form>
					</v-card>
				</v-flex>
			</v-layout>
		</v-container>
	</v-content>
</template>

<script>
  export default {
    data: () => ({
      drawer: null,
      valid: true,
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }),
    props: {
      source: String
    }
  }
</script>

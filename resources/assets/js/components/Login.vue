<template>
	<v-content>
		<v-container fluid fill-height>
			<v-layout align-center justify-center>
				<v-flex xs12 sm8 md4>
					
					<v-container fluid grid-list-md>
						<v-layout row wrap>
							<v-flex>
								<!-- <v-card-media :contain="true" :src="'/assets/img/prem-league.png'" height="225"></v-card-media> -->
								<v-card-media :contain="true" :src="'/assets/img/prem-league.png'" height="225"></v-card-media>
							</v-flex>
						</v-layout>
					</v-container>
					
					<v-card class="elevation-12">
						<v-toolbar dark color="green lighten-1">
							<v-toolbar-title>Login form</v-toolbar-title>
							<v-spacer></v-spacer>
						</v-toolbar>
						<v-card-text>
							<v-form>
								<v-text-field prepend-icon="person" name="login" label="Login" type="text"></v-text-field>
								<v-text-field prepend-icon="lock" name="password" label="Password" id="password" type="password"></v-text-field>
							</v-form>
						</v-card-text>
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn color="green lighten-1 theme--dark">Login</v-btn>
						</v-card-actions>
					</v-card>
				</v-flex>
			</v-layout>
		</v-container>
	</v-content>
</template>

<script>
  export default {
    data: () => ({
      drawer: null
    }),
    props: {
      source: String
    }
  }
</script>

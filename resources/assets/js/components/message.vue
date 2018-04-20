<template>
	<transition name="slide-fade" mode="out-in">
		<article class="message" v-bind:class="[ size, extraClass ]" v-if="show">
			<div class="message-header" v-if="messageTitle">
				<p>{{ messageTitle }}</p>
			</div>
			<div class="message-body">
				{{ message }}
			</div>
		</article>
	</transition>
</template>

<script>
	export default {
		props: {
			messageSize: {
				type: String,
				default: 'normal'
			},
			messageClass: {
				type: String,
				default: ''
			},
			messageTitle: {
				type: String,
				default: ''
			},
			message: {
				type: String
			}
		},

		data: function() {
			return {
				size: this.messageSize,
				extraClass: this.messageClass,
				show: false
			}
		},
		mounted: function() {
			this.show = true;

			setTimeout(() => { this.show = false }, 5000);
		}
	}
</script>
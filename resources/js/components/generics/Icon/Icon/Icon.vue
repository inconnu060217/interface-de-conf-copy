<script>
export default {
  props: {
    name: String,
    className: String,
    cursorPointer: Boolean,
    onClick: Function
  },
  computed: {
    iconClasses() {
      const classes = [this.name, "icon1x"];

      if (this.className) {
        classes.push(this.className);
      }

      if (this.cursorPointer) {
        classes.push("cursor-pointer");
      }

      return classes.join(' ');
    }
  },
  methods: {
    handleClick() {
      if (this.onClick) {
        this.onClick();
      }
    }
  }
};
</script>

<template>
  <i :class="iconClasses" @click="handleClick"></i>
</template>

<style scoped>

.icon1x {
  font-size: 1em;
}


.icon2x {
  font-size: 2em;
}

.icon3x {
  font-size: 3em;
}

.text-Color{
  color: #FFFFFF;
}

</style>

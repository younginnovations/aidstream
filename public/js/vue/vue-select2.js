Vue.component('vue-select2', {
  template: "<div id='select2-container'>" +
  "<input type='text' v-on:focus='display($event)' v-on:blur='hide($event)' placeholder='Please select the following options.' v-model:value='bind_variable[attrText]' v-on:keyup='search($event)' v-bind:readonly = 'disable_options'>" +
  "<input type='text' :value='bind_variable[name]' class='hidden'>" +
  "<ul v-if='displayList' style='position:absolute;z-index:9999!important;max-height:200px;overflow:scroll;border:1px solid #BFDFEC;background-color: #F2F9FC!important'>" +
  "<li v-for='(value,code) in options' :code='code' :value='value' style='padding:5px 10px' :name='name' v-on:click='selected($event)'>{{ value }}</li>" +
  "</ul>" +
  "</div>",
  data: function () {
    return {
      options: [],
      displayList: false,
      fullOptions: [],
      name: '',
      attrText: '',
      text: ''
    }
  },
  props: ['bind_variable', 'disable_options'],
  mounted: function () {
    this.fullOptions = JSON.parse(this.$el.getAttribute('options'));
    this.options = JSON.parse(this.$el.getAttribute('options'));
    this.name = this.$el.getAttribute('name');
    this.attrText = this.$el.getAttribute('attr_name');
    this.text = this.fullOptions[this.bind_variable[this.name]];
    if (!this.bind_variable[this.attrText]) {
      this.bind_variable[this.attrText] = this.text;
    }

  },
  methods: {
    display: function (event) {
      if (!this.disable_options) {
        this.displayList = true;
        this.options = this.fullOptions;
      }
    },
    hide: function (event) {
      var self = this;
      if (this.bind_variable[this.name]) {
        this.bind_variable[this.attrText] = this.fullOptions[this.bind_variable[this.name]];
      } else {
        this.bind_variable[this.attrText] = '';
      }
      setTimeout(function () {
        self.displayList = false;
      }, 300);
    },
    search: function (event) {
      var keyword = event.target.value;
      if (keyword) {
        keyword = keyword.replace(/\[|\]|\\/g, '');
        var regex = new RegExp(keyword, 'i');
        var matches = (typeof(this.fullOptions) === 'object') ? {} : [];
        for (var code in this.fullOptions) {
          if (this.fullOptions[code].match(regex) || code.match(regex)) {
            matches[code] = this.fullOptions[code];
          }
        }

        if (Object.keys(matches).length !== 0 && matches.constructor === Object) {
          this.options = (typeof(this.fullOptions) === 'object') ? {} : [];
          this.options = matches;
        } else {
          this.options = (typeof(this.fullOptions) === 'object') ? { '': 'Please select the following options.' } : ['Please select the following options.'];
        }
      } else {
        this.options = this.fullOptions;
      }
    },
    selected: function (event) {
      this.text = event.target.getAttribute('value');
      this.value = event.target.getAttribute('code');
      var attr = event.target.getAttribute('name');

      if (this.bind_variable) {
        this.bind_variable[attr] = this.value;
        this.bind_variable[this.attrText] = this.text;
      }
      this.$emit('change', { name: attr, value: this.value });
      this.displayList = false;
    }
  }
});
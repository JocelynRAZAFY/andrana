import BaseButton from "../components/BaseButton";
import Card from "../components/Card";

export default {
  install(Vue) {
    Vue.component(BaseButton.name, BaseButton);
    Vue.component(Card.name, Card);
  }
};

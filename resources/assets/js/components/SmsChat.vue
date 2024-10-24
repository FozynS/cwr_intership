<template>
  <div class="sms-tab">
    <div v-for="(messageGroup, index) in groupedMessages" :key="index">
      <div class="message-date">{{ formatDate(messageGroup.date) }}</div>
      <div
        v-for="message in messageGroup.messages"
        :key="message.id"
        class="message"
        :class="{
          'message-sent': message.direction === 'outbound',
          'message-received': message.direction === 'inbound',
        }"
      >
        <div class="message-author">{{ getAuthor(message) }}</div>
        <div class="message-text-container">
          <div class="message-text">{{ message.message_body }}</div>
          <div class="message-time">{{ formatTime(message.created_at) }}</div>
        </div>
      </div>
    </div>

    <infinite-loading @infinite="loadMoreMessages"></infinite-loading>

    <div class="send-message-container">
      <div class="select-container">
        <select v-model="selectedPhone" class="phone-select">
          <option :value="patient.cell_phone">
            {{ formatPhone(patient.cell_phone) }}
          </option>
          <option :value="patient.home_phone">
            {{ formatPhone(patient.home_phone) }}
          </option>
          <option :value="patient.work_phone">
            {{ formatPhone(patient.work_phone) }}
          </option>
        </select>
      </div>

      <textarea
        v-model="newMessage"
        placeholder="Enter your message"
        @keydown.enter="sendMessage"
        class="message-input-section"
      ></textarea>
      <button @click="sendMessage">Submit</button>
    </div>
  </div>
</template>

<script>
import InfiniteLoading from "vue-infinite-loading";
import axios from "axios";

export default {
  components: {
    InfiniteLoading,
  },
  props: {
    patientId: {
      type: Number,
      required: true,
    },
    patient: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      newMessage: "",
      selectedPhone: this.patient.cell_phone,
      messages: [],
      currentPage: 1,
      totalPages: 0,
      infiniteDisabled: false,
    };
  },
  computed: {
    groupedMessages() {
      return this.groupMessagesByDate(this.messages);
    },
  },
  methods: {
    fetchMessages() {
      axios
        .get(`/api/patients/${this.patientId}/sms`)
        .then((response) => {
          this.messages = response.data.data.data.sort(
            (a, b) => new Date(a.created_at) - new Date(b.created_at)
          );
          this.totalPages = response.data.pagination.last_page;
          this.currentPage = response.data.pagination.current_page;
        })
        .catch((error) => {
          console.error("Error loading messages:", error);
        });
    },

    groupMessagesByDate(messages) {
      const grouped = {};
      messages.forEach((message) => {
        const date = message.created_at.split(" ")[0];
        if (!grouped[date]) {
          grouped[date] = { date, messages: [] };
        }
        grouped[date].messages.push(message);
      });

      const groupedArray = Object.values(grouped).sort(
        (a, b) => new Date(a.date) - new Date(b.date)
      );

      return groupedArray;
    },

    formatDate(date) {
      if (!date) {
        console.error("Invalid date format for:", date);
        return "Unknown date";
      }
      const parsedDate = new Date(date);
      if (isNaN(parsedDate.getTime())) {
        console.error("Invalid date format for:", date);
        return "Unknown date";
      }

      const options = {
        weekday: "long",
        year: "numeric",
        month: "short",
        day: "2-digit",
      };

      const formattedDate = parsedDate.toLocaleDateString("en-GB", options);
      return formattedDate.replace(/(\w+)\s(\d{2})\s(\d{4})/, "$1, $2 $3.");
    },

    formatTime(dateTime) {
      if (!dateTime) {
        console.error("Invalid date-time format provided:", dateTime);
        return "";
      }
      
      const timePart = dateTime.split(" ")[1];
      const [hours, minutes] = timePart.split(":");

      return `${hours}:${minutes}`;
    },

    formatPhone(phone) {
      const cleaned = phone.replace(/\D/g, "");
      let match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);

      if (match) {
        return `(${match[1]})-${match[2]}-${match[3]}`;
      }
      return phone;
    },

    getAuthor(message) {
      return message.direction === "inbound" ? "Patient" : message.author;
    },

    sendMessage() {
      if (this.newMessage.trim() === "") {
        alert("Please enter a message.");
        return;
      }

      const payload = {
        to_number: this.selectedPhone,
        message: this.newMessage,
      };

      axios
        .post(`/api/patients/${this.patientId}/sms/send`, payload)
        .then((response) => {
          console.log("Message sent successfully:", response.data);

          const messageData = response.data;
          const now = new Date();
          messageData.date =
            messageData.date || now.toISOString().split("T")[0];
          messageData.time =
            messageData.time || now.toTimeString().split(" ")[0];

          this.messages.push(messageData);
          console.log(this.messages);
          this.newMessage = "";
        })
        .catch((error) => {
          console.error("Error when sending a message:", error);
        });
    },

    loadMoreMessages($state) {
      if (this.currentPage >= this.totalPages) {
        $state.complete();
        return;
      }

      axios
        .get(`/api/patients/${this.patientId}/sms/page/${this.currentPage + 1}`)
        .then((response) => {
          const newMessages = response.data.data;
          if (newMessages.length) {
            this.messages.push(...newMessages);
            this.messages.sort((a, b) => new Date(a.date) - new Date(b.date));
            this.currentPage++;
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch((error) => {
          console.error("Error loading additional messages:", error);
          $state.complete();
        });
    },
  },
  mounted() {
    this.fetchMessages();
  },
};
</script>

<style scoped>
.sms-tab {
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  max-width: 95%;
  margin: 0 auto;
  margin-bottom: 50px;
  background-color: #f9f9f9;
  border: 1px solid gray;
  border-radius: 5px;
}

.message-date {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 50px;
  color: #2e2e2e;
  margin: 20px 0 10px;
  background: #adadad;
  text-align: center;
  font-size: 18px;
  font-weight: bold;
}

.message-container {
  margin-bottom: 20px;
}

.message {
  margin-bottom: 10px;
  width: 45%;
  padding: 10px 15px;
  position: relative;
}

.message-sent {
  margin-left: auto;
  margin-right: 30px;
}
.message-sent .message-text-container {
  display: flex;
  flex-direction: row-reverse;
  justify-content: space-between;
  box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
  background-color: #b3d8ff;
}
.message-sent .message-time {
  margin-left: 20px;
}
.message-sent .message-author {
  text-align: right;
}

.message-received {
  text-align: left;
  margin-right: auto;
  margin-left: 30px;
}
.message-received .message-text-container {
  background-color: #ccc;
  box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
}
.message-received .message-time {
  margin-right: 20px;
}

.message-author {
  font-size: 12px;
  font-weight: bold;
  color: #333;
  margin-bottom: 5px;
}

.message-text-container {
  display: flex;
  align-items: center;
  border-radius: 8px;
}

.message-text {
  display: flex;
  font-size: 14px;
  color: #333;
  min-height: 50px;
  padding: 10px;
  border-radius: 5px;
  justify-content: center;
  align-items: center;
}

.message-time {
  font-size: 13px;
  color: #999;
}

.send-message-container {
  display: flex;
  height: 70px;
  width: 100%;
  align-items: center;
}
.select-container {
  display: flex;
  justify-content: center;
  align-items: center;
  background: #9f9f9f;
  width: 20%;
  height: 100%;
}

.message-input-section {
  display: flex;
  flex-direction: column;
}

.phone-select {
  border: 1px solid #ccc;
  border-radius: 5px;
  width: 90%;
  height: 40%;
}
.phone-select:active {
  border: 1.5px solid #63c2f1;
}

textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  height: 100%;
  resize: none;
}

button {
  background-color: #007bff;
  color: white;
  padding: 10px 15px;
  border: none;
  border-bottom-right-radius: 4px;
  cursor: pointer;
  width: 10%;
  height: 100%;
}

button:hover {
  background-color: #0056b3;
}

.infinite-loading-wrapper {
  text-align: center;
  padding: 15px;
}
</style>

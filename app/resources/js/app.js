import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.appShell = () => ({
    toast: {
        open: false,
        message: '',
        type: 'success',
    },
    darkMode: true,
    init() {
        const storedTheme = localStorage.getItem('laravel-ai-starter:dark-mode');

        this.darkMode = storedTheme === null ? true : storedTheme === 'true';
        document.documentElement.classList.toggle('dark', this.darkMode);

        if (window.__FLASH_TOAST__) {
            this.notify(window.__FLASH_TOAST__);
            delete window.__FLASH_TOAST__;
        }

        window.addEventListener('notify', (event) => {
            this.notify(event.detail?.message ?? 'Done', event.detail?.type ?? 'success');
        });
    },
    toggleDarkMode(force = null) {
        this.darkMode = force === null ? !this.darkMode : force;
        document.documentElement.classList.toggle('dark', this.darkMode);
        localStorage.setItem('laravel-ai-starter:dark-mode', String(this.darkMode));
    },
    notify(message, type = 'success') {
        this.toast = { open: true, message, type };

        clearTimeout(this.toastTimeout);
        this.toastTimeout = setTimeout(() => {
            this.toast.open = false;
        }, 3500);
    },
});

window.chatPage = (config) => ({
    conversationId: config.conversationId,
    messagesEndpoint: config.messagesEndpoint,
    messages: (config.messages ?? []).map((message) => ({
        ...message,
        display_html: message.status === 'completed' ? message.content_html : '',
    })),
    copiedId: null,
    pollTimer: null,
    init() {
        this.scrollToBottom();
        this.startPolling();
    },
    startPolling() {
        if (!this.conversationId || !this.messagesEndpoint) {
            return;
        }

        if (!this.hasPendingMessages()) {
            return;
        }

        this.pollTimer = setInterval(() => this.fetchMessages(), 2500);
    },
    hasPendingMessages() {
        return this.messages.some((message) => message.status === 'pending');
    },
    async fetchMessages() {
        try {
            const response = await window.axios.get(this.messagesEndpoint);
            const nextMessages = response.data.data ?? [];
            const previousById = new Map(this.messages.map((message) => [message.id, message]));

            this.messages = nextMessages.map((message) => {
                const previous = previousById.get(message.id);

                if (previous && previous.status === 'pending' && message.status === 'completed') {
                    previous.status = 'completed';
                    previous.content_markdown = message.content_markdown;
                    previous.content_html = message.content_html;
                    previous.error_message = message.error_message;
                    previous.display_html = '';

                    this.typeAssistantMessage(previous);

                    return previous;
                }

                return {
                    ...message,
                    display_html: previous?.display_html || message.content_html || '',
                };
            });

            if (!this.hasPendingMessages() && this.pollTimer) {
                clearInterval(this.pollTimer);
                this.pollTimer = null;
            }

            this.$nextTick(() => this.scrollToBottom());
        } catch (error) {
            if (this.pollTimer) {
                clearInterval(this.pollTimer);
                this.pollTimer = null;
            }

            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    message: 'Polling stopped because the conversation status could not be refreshed.',
                    type: 'error',
                },
            }));
        }
    },
    typeAssistantMessage(target) {
        const plainText = target.content_markdown ?? '';
        let index = 0;

        target.display_html = '';

        const interval = setInterval(() => {
            index += 2;

            const typedText = plainText.slice(0, index);
            target.display_html = `<p>${this.escapeHtml(typedText).replace(/\n/g, '<br>')}</p>`;

            this.$nextTick(() => this.scrollToBottom());

            if (index >= plainText.length) {
                clearInterval(interval);
                target.display_html = target.content_html;
            }
        }, 18);
    },
    async copyMessage(message) {
        await navigator.clipboard.writeText(message.content_markdown ?? '');
        this.copiedId = message.id;

        setTimeout(() => {
            if (this.copiedId === message.id) {
                this.copiedId = null;
            }
        }, 1500);
    },
    scrollToBottom() {
        if (this.$refs.messageContainer) {
            this.$refs.messageContainer.scrollTop = this.$refs.messageContainer.scrollHeight;
        }
    },
    escapeHtml(value) {
        return value
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    },
});

Alpine.start();

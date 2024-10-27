
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
const SpeechSynthesis = window.speechSynthesis;
if (!SpeechRecognition||!SpeechSynthesis) {
    console.error('\'Ваш браузер не поддерживает Speech Recognition и Speech Synthesis\'')
} else {
    const recognition = new SpeechRecognition();
    recognition.interimResults = true;
    recognition.lang = 'ru-RU';

    let isListeningForCommand = false;

    recognition.addEventListener('result', (event) => {
        let transcript = '';

        for (const result of event.results) {
            if (result.isFinal) {
                transcript = result[0].transcript.trim();
            }
        }


        if (!isListeningForCommand) {
            if (transcript.toLowerCase().includes('ассистент')) {
                speak("слушаю")

                isListeningForCommand = true;
                ChangeState();

            }
        } else {
            if (transcript) {
                handleCommand(transcript);
                isListeningForCommand = false;
                ChangeState();
            }
        }
    });

    recognition.addEventListener('end', recognition.start);

    recognition.start();

    function ChangeState(){
        const customEvent = new CustomEvent('changeState', {
            detail: {
                status: isListeningForCommand
            }
        });
        document.dispatchEvent(customEvent);
    }
    function speak(text) {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'ru-RU';
        utterance.pitch = 1;

        window.speechSynthesis.speak(utterance);
    }
}

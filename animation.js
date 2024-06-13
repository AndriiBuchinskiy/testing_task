document.addEventListener("DOMContentLoaded", function() {
    const textBlock = document.getElementById('text-block');
    const colors = ['red', 'orange', 'yellow', 'green', 'blue', 'indigo', 'violet'];
    let colorIndex = 0;
    let direction = 1;

    function changeColor() {
        textBlock.style.color = colors[colorIndex];
        colorIndex += direction;

        if (colorIndex === colors.length - 1 || colorIndex === 0) {
            direction *= -1;
        }
    }

    setInterval(changeColor, 4000);
    changeColor();
});

<?php

declare(strict_types=1);

namespace Phpml\Classification;

use Phpml\Exception\InvalidArgumentException;
use Phpml\NeuralNetwork\Network\MultilayerPerceptron;

class MLPClassifier extends MultilayerPerceptron implements Classifier
{
    /**
     * @param mixed $target
     *
     * @throws InvalidArgumentException
     */
    public function getTargetClass($target) : int
    {
        if (!in_array($target, $this->classes)) {
            throw InvalidArgumentException::invalidTarget($target);
        }

        return array_search($target, $this->classes);
    }

    /**
     * @return mixed
     */
    protected function predictSample(array $sample)
    {
        $output = $this->setInput($sample)->getOutput();

        $predictedClass = null;
        $max = 0;
        foreach ($output as $class => $value) {
            if ($value > $max) {
                $predictedClass = $class;
                $max = $value;
            }
        }

        return $this->classes[$predictedClass];
    }

    /**
     * @param mixed $target
     */
    protected function trainSample(array $sample, $target): void
    {

        // Feed-forward.
        $this->setInput($sample)->getOutput();

        // Back-propagate.
        $this->backpropagation->backpropagate($this->getLayers(), $this->getTargetClass($target));
    }
}
